<?php

namespace App\Exceptions;

use App\Toolkit\ResponseApi;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        //不存在路由捕获
        if ($exception instanceof NotFoundHttpException) {
            return ResponseApi::ApiError('路由不存在', [], ResponseApi::API_ROUTER_NOTHINGNESS);
        }

        //请求路由方式不正确异常捕获
        if ($exception instanceof MethodNotAllowedHttpException) {
            return ResponseApi::ApiError('路由请求方式不正确', [], ResponseApi::API_METHOD_ERROR);
        }

        //Api错误返回异常捕获
        if ($exception instanceof ApiResponseExceptions) {
            return ResponseApi::ApiError($exception->getMessage(), [], ResponseApi::PARAMETER_ERROR);
        }

        //未登陆状态的错误异常捕获
        if ($exception instanceof AuthenticationException) {
            if ($request->ajax()) {
                return ResponseApi::ApiError('请先进行登录验证', [], ResponseApi::NOT_LOGIN_ERROR);
            }
        }

        return parent::render($request, $exception);
    }
}
