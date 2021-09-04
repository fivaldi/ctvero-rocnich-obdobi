<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

use App\Exceptions\ForbiddenException;

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
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ForbiddenException) {
            return response(view('error', [ 'msg' => 'Přístup odepřen.' ]))->setStatusCode(403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response(view('error', [ 'msg' => 'Stránka nebyla nalezena.' ]))->setStatusCode(404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response(view('error', [ 'msg' => 'Metoda není povolena.' ]))->setStatusCode(405);
        }

        if ($exception instanceof QueryException) {
            return response(view('error', [ 'msg' => 'Došlo k chybě!' ]))->setStatusCode(500);
        }

        return parent::render($request, $exception);
    }
}
