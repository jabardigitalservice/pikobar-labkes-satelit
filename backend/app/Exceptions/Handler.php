<?php

namespace App\Exceptions;

// use Exception;
use App\Traits\ApiResponser;
// use Asm89\Stack\CorsService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;

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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)
            && !app()->environment('local')) {
            app('sentry')->captureException($exception);
        }

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Throwable $exception)
    {
        $response = $this->handleException($request, $exception);
        // app(CorsService::class)->addActualRequestHeaders($response, $request);

        return $response;
    }

    public function handleException($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        } elseif ($exception instanceof ModelNotFoundException) {
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse(
                "Does not exists any {$modelName} with the specified identificator",
                404
            );
        } elseif ($exception instanceof AuthenticationException) {
            if ($this->isFrontend($request)) {
                return redirect()->guest('login');
            } else {
                return $this->errorResponse('Unauthenticated', 401);
            }
        } elseif ($exception instanceof AuthorizationException) {
            return $this->errorResponse($exception->getMessage(), 403);
        } elseif ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The specified URL cannot be found.', 404);
        } elseif ($exception instanceof RouteNotFoundException) {
            return $this->errorResponse('The specified Route cannot be found.', 404);
        } elseif ($exception instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('The specified method for the requests is invalid', 405);
        } elseif ($exception instanceof HttpException) {
            return $this->errorResponse(
                $exception->getMessage(),
                $exception->getStatusCode()
            );
        } elseif ($exception instanceof QueryException) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode == 1451) {
                return $this->errorResponse(
                    'Cannot remove the resources permanently. It is related with any other resource.',
                    409
                );
            }
        } elseif ($exception instanceof TokenMismatchException) {
            return redirect()->back()->withInput($request->input());
        } elseif (config('app.debug')) {
            return parent::render($request, $exception);
        } else {
            return $this->errorResponse(
                'Unexpected Exception, Try later.',
                500
            );
        }
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function invalidJson($request, ValidationException $exception)
    {
        /**
         * for getting error message $exception->getMessage()
         */
        return $this->errorResponse(
            $exception->errors(),
            $exception->status
        );

    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->errors();

        if ($this->isFrontend($request)) {
            return $request->ajax()
            ? response()->json($errors, 422)
            : redirect()->back()->withInput($request->input())->withErrors($errors);
        } else {
            return $this->invalidJson($request, $e);
        }
    }

    public function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
