<?php

namespace App\Http\Middleware;

use Laravel\Passport\Http\Middleware\CheckClientCredentials as BaseMiddleware;
use Closure;
use League\OAuth2\Server\ResourceServer;
use Illuminate\Auth\AuthenticationException;
use Laravel\Passport\Exceptions\MissingScopeException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Lcobucci\JWT\Parser;
use Modules\Open\Services\ClientService;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class CheckClientCredentials extends BaseMiddleware{
    /**
     * The Resource Server instance.
     *
     * @var \League\OAuth2\Server\ResourceServer
     */
    private $server;

    protected $clientService;

    /**
     * Create a new middleware instance.
     *
     * @param  \League\OAuth2\Server\ResourceServer  $server
     * @return void
     */
    public function __construct(ResourceServer $server,ClientService $clientService)
    {
        $this->server = $server;
        $this->clientService = $clientService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed  ...$scopes
     * @return mixed
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next, ...$scopes)
    {
        $psr = (new DiactorosFactory)->createRequest($request);

        try {
            $psr = $this->server->validateAuthenticatedRequest($psr);
        } catch (OAuthServerException $e) {
            $hint = $e->getHint();
            if($hint == 'Access token is invalid'){
                throw new UnauthorizedHttpException("check-token",'Access token is expired',null,config('exceptions.TOKEN_EXPIRES'));
            }else{
                throw new UnauthorizedHttpException("check-token",$e->getHint(),null,config('exceptions.TOKEN_INVALID'));
            }
        }

        $this->validateScopes($psr, $request->scopes);

        $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/','',$request->header('Authorization')));
        $accessToken = (new Parser())->parse($jwt);

        $clientID = $accessToken->getClaim('aud');

        $user = $this->clientService->getUser($clientID);
        if(empty($user)){
            throw new UnauthorizedHttpException("check-token",'No Server Url',null,config('exceptions.CLIENT_NO_SERVER_URL'));
        }

        //add user to request
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }

    /**
     * Validate the scopes on the incoming request.
     *
     * @param  \Psr\Http\Message\ResponseInterface $psr
     * @param  array  $scopes
     * @return void
     * @throws \Laravel\Passport\Exceptions\MissingScopeException
     */
    protected function validateScopes($psr, $scopes)
    {
        if(empty($scopes)){
            throw new \Exception("The token no scopes",config('exceptions.SCOPE_INVALID'));
        }

        if (in_array('*', $tokenScopes = $psr->getAttribute('oauth_scopes'))) {
            return;
        }

        foreach ($scopes as $scope) {
            if (! in_array($scope, $tokenScopes)) {
                throw new \Exception("The token no this scope[".$scope."]",config('exceptions.SCOPE_INVALID'));
            }
        }
    }
}
