<?php

namespace App\Exceptions\Handlers;

use App\Exceptions\AccountLockedException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\InactiveAccountException;
use App\Exceptions\InvalidOtpException;
use App\Http\Responses\ApiResponse;
use Illuminate\Auth\AuthenticationException as IlluminateAuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

/**
 * API exception handler for consistent error responses
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles exception conversion to responses
 * - Open/Closed Principle: Can be extended for additional exception types
 * - Liskov Substitution Principle: Can be substituted with other handlers
 * - Interface Segregation Principle: Provides only exception handling methods
 * - Dependency Inversion Principle: Depends on abstractions, not concretions
 */
class ApiExceptionHandler
{
    /**
     * Render an exception into an HTTP response.
     */
    public function render(Request $request, Throwable $exception): JsonResponse
    {
        // Handle our custom exceptions
        if ($exception instanceof AccountLockedException) {
            return ApiResponse::error($exception->getMessage(), 423);
        }

        if ($exception instanceof InactiveAccountException) {
            return ApiResponse::error($exception->getMessage(), 403);
        }

        if ($exception instanceof InvalidOtpException) {
            return ApiResponse::error($exception->getMessage(), 400);
        }

        if ($exception instanceof AuthenticationException) {
            return ApiResponse::error($exception->getMessage(), 401);
        }

        // Handle Laravel's built-in exceptions
        if ($exception instanceof IlluminateAuthenticationException) {
            return ApiResponse::unauthorized('Unauthenticated');
        }

        if ($exception instanceof ModelNotFoundException) {
            return ApiResponse::notFound('Resource not found');
        }

        if ($exception instanceof NotFoundHttpException) {
            return ApiResponse::notFound('Endpoint not found');
        }

        if ($exception instanceof ValidationException) {
            return ApiResponse::validationError($exception->errors(), $exception->getMessage());
        }

        // Handle all other exceptions
        return $this->handleGenericException($exception);
    }

    /**
     * Handle generic exceptions
     */
    protected function handleGenericException(Throwable $exception): JsonResponse
    {
        // Log the exception for debugging
        \Log::error('Unhandled exception: '.$exception->getMessage(), [
            'exception' => $exception,
            'trace' => $exception->getTraceAsString(),
        ]);

        // Return a generic error response
        if (config('app.debug')) {
            return ApiResponse::serverError($exception->getMessage());
        }

        return ApiResponse::serverError('An unexpected error occurred');
    }
}
