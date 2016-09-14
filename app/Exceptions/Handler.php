<?php

namespace Ventamatic\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Ventamatic\Core\Product\Product;
use Ventamatic\Http\Response;

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
     * @param  \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($request->wantsJson()) {
            Log::error($e);

            if ($e instanceof ModelNotFoundException) {
                $message = $this->renderModelNotFoundException($e);
                Log::error($message);
                if($message){
                    return Response::error(10,$message);
                }
            }

            if ($e instanceof PermissionException) {
                return Response::error(100, 'No cuenta con permiso para: '.$e->getMessage());
            }

            return Response::error(500, 'Exception: ' . class_basename($e) .
                ' in ' . basename($e->getFile()) . ' line ' .
                $e->getLine() . ': ' . $e->getMessage());
        }
        return parent::render($request, $e);
    }

    private function renderModelNotFoundException(ModelNotFoundException $e)
    {
        switch ($e->getModel()) {
            case Product::class:
                return \Lang::get('products.not_found_id');
            default:
                return 'No se encontró: '.$e->getModel();
        }
    }
}
    
