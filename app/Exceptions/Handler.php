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
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

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
        if ($exception instanceof UnauthorizedHttpException) {
            return $this->errorJsonOrErrorPageResponse(__('Je vyžadováno přihlášení.'), 401);
        }

        if ($exception instanceof ForbiddenException) {
            return $this->errorJsonOrErrorPageResponse(__('Přístup odepřen.'), 403);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->errorJsonOrErrorPageResponse(__('Stránka nebyla nalezena.'), 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorJsonOrErrorPageResponse(__('Metoda není povolena.'), 405);
        }

        if ($exception instanceof QueryException) {
            return $this->errorJsonOrErrorPageResponse(__('Došlo k chybě!'), 500);
        }

        return parent::render($request, $exception);
    }

    public static function errorJsonOrErrorPageResponse($message, $statusCode)
    {
        if (request()->ajax()) {
            return response()->json([ 'errors' => array($message) ])->setStatusCode($statusCode);
        }
        return response(view('error', [ 'msg' => $message ]))->setStatusCode($statusCode);
    }
}
