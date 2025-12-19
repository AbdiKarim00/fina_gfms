<?php

namespace App\Exceptions;

use App\Exceptions\Handlers\ApiExceptionHandler;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

/**
 * Application exception handler
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles application exceptions
 * - Open/Closed Principle: Can be extended for additional exception handling
 * - Liskov Substitution Principle: Can substitute for ExceptionHandler
 * - Interface Segregation Principle: Provides only exception handling methods
 * - Dependency Inversion Principle: Depends on abstractions, not concretions
 */
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
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Use our API exception handler for API requests
        if ($request->expectsJson()) {
            $apiHandler = new ApiExceptionHandler;

            return $apiHandler->render($request, $exception);
        }

        // Fall back to Laravel's default exception handling
        return parent::render($request, $exception);
    }
}
