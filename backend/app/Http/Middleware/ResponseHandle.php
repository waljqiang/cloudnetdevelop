<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Zend\Diactoros\Stream;
use Illuminate\Auth\AuthenticationException;

class ResponseHandle{

    //不需校验路由
    protected $except = [

    ];

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next){
        /**
         * @var Response $response
         */
        $response = $next($request);
        if ($this->inExceptArray($request)) {
            return $response;
        }

        if ($response->getStatusCode() == 429) {
            return $this->makeErrorResponse('Too many attempts', [config('exceptions.THROTTLE_ERROR')]);
        }

        if ($response->getStatusCode() == 430){
            return $this->makeErrorResponse('API calls exceed limits', [config('exceptions.LIMIT_ERROR')]);
        }

        if ($response->exception) {

            if ($response->exception instanceof UnauthorizedHttpException) {
                return $this->makeErrorResponse($response->exception->getMessage(), [$response->exception->getCode()]);
            }elseif ($response->exception instanceof ValidationException) {

                $errorCode = array_map(function ($val) {
                    return (int)$val;
                }, $response->exception->validator->errors()->all());
                return $this->makeErrorResponse('Invalid parameters', $errorCode);
            }elseif($response->exception instanceof AuthenticationException){
                return $this->makeErrorResponse($response->exception->getMessage(), [config('exceptions.TOKEN_INVALID')]);
            }else{

                if (in_array($response->exception->getCode(), array_values(config('exceptions')))) {
                    return $this->makeErrorResponse($response->exception->getMessage(), [$response->exception->getCode()]);
                }

                if (env('APP_ENV') != 'production') {
                    return $this->makeErrorResponse($response->exception->getMessage());
                } else {
                    return $this->makeErrorResponse('internal error');
                }
            }
        }

        $original = $response->original instanceof Stream ? json_decode($response->original,true) : $response->original;

        return $this->makeSuccessResponse($original);
    }

    /**
     * @param $message
     * @param $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeErrorResponse($message = '', $errorCode = [10001]){
        return $this->makeResponse(config('exceptions.ERROR'), [], $message, $errorCode);
    }

    /**
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeSuccessResponse($data){
        return $this->makeResponse(config('exceptions.SUCCESS'), $data, '', []);
    }

    /**
     * @param $status
     * @param $data
     * @param $message
     * @param $errorCode
     * @return \Illuminate\Http\JsonResponse
     */
    protected function makeResponse($status = 10000, $data = [], $message = '', $errorCode = []){
        if (!$message) {
            return response()->json(compact('status', 'data', 'errorCode'));
        }
        return response()->json(compact('status', 'data', 'message', 'errorCode'));
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function inExceptArray(Request $request){
        foreach ($this->except as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->is($except)) {
                return true;
            }
        }
        return false;
    }

}
