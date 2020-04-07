<?php

namespace App\Exceptions;

// use Exception;
use App\Traits\ApiResponser;
// use Asm89\Stack\CorsService;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        if ($exception instanceof ValidationException)
        {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof ModelNotFoundException)
        {
            $modelName = strtolower(class_basename($exception->getModel()));
            return $this->errorResponse(
                "Does not exists any {$modelName} with the specified identificator",
                404
            );
        }

        if ($exception instanceof AuthenticationException)
        {
            if ($this->isFrontend($request))
            {
                return redirect()->guest('login');
            }
            return $this->errorResponse('Unauthenticated', 401);
        }

        if ($exception instanceof AuthorizationException)
        {
            return $this->errorResponse($exception->getMessage(), 403);
        }

        if ($exception instanceof NotFoundHttpException)
        {
            return $this->errorResponse('The specified URL cannot be found.', 404);
        }

        if ($exception instanceof RouteNotFoundException)
        {
            return $this->errorResponse('The specified Route cannot be found.', 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException)
        {
            return $this->errorResponse('The specified method for the requests is invalid', 405);
        }

        if ($exception instanceof HttpException)
        {
            return $this->errorResponse($exception->getMessage(), $exception->getStatusCode());
        }

        if ($exception instanceof QueryException)
        {
            $errorCode = $exception->errorInfo[1];

            if ($errorCode == 1451)
            {
                return $this->errorResponse(
                    'Cannot remove the resources permanently. It is related with any other resource.',
                    409
                );
            }
        }

        if ($exception instanceof TokenMismatchException)
        {
            return redirect()->back()->withInput($request->input());
        }

        if (config('app.debug'))
        {
            return parent::render($request, $exception);
        }

        return $this->errorResponse('Unexpected Exception, Try later.', 500);
    }

    /**
     * Convert a validation exception into a JSON response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Validation\ValidationException  $exception
     * @return \Illuminate\Http\JsonResponse
     */
    protected function invalidJson($request, ValidationException $exception)
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
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {

        $errors = $e->errors();

        if ($this->isFrontend($request))
        {
            return $request->ajax()
                ? response()->json($errors, 422)
                : redirect()->back()->withInput($request->input())->withErrors($errors);
        }

        return $this->invalidJson($request, $e);
    }

    private function isFrontend($request)
    {
        return $request->acceptsHtml() && collect($request->route()->middleware())->contains('web');
    }
}
