<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use \Symfony\Component\HttpKernel\Exception\HttpException;
use \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Predis\ClientException;
use Illuminate\Database\QueryException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception){
        if($exception instanceof NotFoundHttpException){
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => "Http Request not found","errorCode" => [config("exceptions.HTTP_REQUEST_NO_EXISTS")]],401);
        }elseif($exception instanceof MethodNotAllowedHttpException){
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => "Not allowed http request method","errorCode" => [config("exceptions.HTTP_NO_ALLOWED_METHOD")]],401);
        }elseif ($exception instanceof HttpException){
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => "HTTP request error","errorCode" => [config('exceptions.HTTP_REQUEST_NO_EXISTS')]],401);
        }elseif($exception instanceof AuthenticationException){
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => $exception->getMessage(),"errorCode" => [config("exceptions.AUTH_NO")]],401);
        }elseif($exception instanceof ClientException){
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => $exception->getMessage(),"errorCode" => [config("exceptions.REDIS_CONNECT_ERROR")]]);
        }elseif($exception instanceof QueryException){
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => $exception->getMessage(),"errorCode" => [config("exceptions.MYSQL_EXEC_ERROR")]]);
        }else{
            return response()->json(["status" => config("exceptions.ERROR"),"data" => [],"message" => $exception->getMessage(),"errorCode" => [config("exceptions.ERROR")]],401); 
        }
        return parent::render($request, $exception);
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
