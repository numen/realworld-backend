<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenInvalidException;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        /*
        $this->renderable(function (Throwable $e) {
    if ($e instanceof TokenExpiredException) {
        return response()->json(['error' => 'Token ha expirado'], 401);
    } elseif ($e instanceof TokenInvalidException) {
        return response()->json(['error' => 'Token inválido'], 401);
    } elseif ($e instanceof JWTException) {
        return response()->json(['error' => 'Token no proporcionado'], 401);
    }
        });
*/
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e)
    {
        /*
        if($e instanceof NotFoundHttpException) {
        return response()->json([
            'message' => 'From render method: Resource not found'
        ], Response::HTTP_NOT_FOUND);
        }
        */

        if ($e instanceof TokenExpiredException) {
            return response()->json(['error' => 'Token ha expirado'], 401);
        } elseif ($e instanceof TokenInvalidException) {
            return response()->json(['error' => 'Token inválido'], 401);
        } elseif ($e instanceof JWTException) {
            return response()->json(['error' => 'Token no proporcionado'], 401);
        }
        return parent::render($request, $e);
    }
}
