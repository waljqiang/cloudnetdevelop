<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Carbon\Carbon;
use App\Utils\RateLimiter;
use Symfony\Component\HttpFoundation\Response;
use Lcobucci\JWT\Parser;
use Illuminate\Auth\AuthenticationException;

class LimitRequest{
    /**
     * The rate limiter instance.
     *
     * @var \Illuminate\Cache\RateLimiter
     */
    protected $limiter;

    /**
     * Create a new request throttler.
     *
     * @param  \Illuminate\Cache\RateLimiter  $limiter
     * @return void
     */
    public function __construct(RateLimiter $limiter)
    {
        $this->limiter = $limiter;
    }

    public function handle(Request $request, Closure $next, $dayMax = 100, $monthMax = 2000){
        if(!empty($request->header('Authorization'))){
            try{
                $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/','',$request->header('Authorization')));
                $accessToken = (new Parser())->parse($jwt);
            }catch(\Exception $e){
                throw new AuthenticationException('Token invalid');
            }

            $clientID = $accessToken->getClaim('aud');
            $key = app('Hashids')->encodeHash($clientID);
        }else{
            $key = array_get($request->all(),'client_id');
        }

        if ($this->limiter->tooManyAttempts($key, $dayMax, $monthMax)) {

            return $this->buildResponse($key, $dayMax,$monthMax);
        }

        $rs = $this->limiter->hit($key);

        $response = $next($request);

        return $response;
    }

    /**
     * Resolve request signature.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function resolveRequestSignature($request){
        return $request->fingerprint();
    }

    /**
     * Create a 'too many attempts' response.
     *
     * @param  string  $key
     * @param  int  $dayMax
     * @param  int $monthMax
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function buildResponse($key, $dayMax,$monthMax){
        $response = new Response('Too Many Attempts.', 430);
        return $response;
    }

}
