<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if($e instanceof PremiumDomainBusinessSupportException){
            $e = new NoticeMessageException($e->getCHNExceptionNoticeMessage());
        }else if ($e instanceof HttpException) {
            $e = new HttpException($e->getStatusCode(), $e->getMessage(), $e, $e->getHeaders(), $e->getStatusCode());
        }else if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }else if($e instanceof TokenMismatchException){
            $e = new NoticeMessageException('页面过期，请刷新！', 410);
        }else if ($e instanceof \Swift_TransportException) {
            $e = new NoticeMessageException('邮箱系统异常，请联系管理员！');
        }

        $code = $e->getCode();
        $message = $e->getMessage();
        if($code< 100 || $code >= 600){
            $code = 512;  //运行时异常
            $message = env('APP_DEBUG') ? $message : '服务器运行时错误!';
        }

        $errors = ['msg'=>$message, 'code'=>$code];


        if ($request->ajax() || $request->wantsJson()) {
            return new JsonResponse($errors, $code);
        }
        return parent::render($request, $e);
    }
}
