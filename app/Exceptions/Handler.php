<?php

namespace App\Exceptions;

use App\Helpers\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Throwable;

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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof AuthorizationException) {
            return ApiResponse::error(
                'FORBIDDEN',
                $exception->getMessage() ?: 'You are not authorized to perform this action.',
                [],
                ApiResponse::FORBIDDEN_STATUS
            );
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return ApiResponse::error(
                'TOO_MANY_REQUESTS',
                $exception->getMessage() ?: 'Too many requests.',
                [],
                ApiResponse::TOO_MANY_REQUESTS_STATUS
            );
        }

        return parent::render($request, $exception);
    }
}
