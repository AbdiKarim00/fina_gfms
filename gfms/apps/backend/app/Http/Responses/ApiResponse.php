<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

/**
 * Standardized API response formatter
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles response formatting
 * - Open/Closed Principle: Can be extended for different response types
 * - Liskov Substitution Principle: Can be substituted with other response formatters
 * - Interface Segregation Principle: Provides only response formatting methods
 * - Dependency Inversion Principle: Depends on abstractions, not concretions
 */
class ApiResponse
{
    /**
     * Create a successful response
     */
    public static function success($data = null, string $message = 'Success', int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Create an error response
     */
    public static function error(string $message = 'Error', int $code = 400, $errors = null): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors) {
            $response['errors'] = $errors;
        }

        // Include debug information in development
        if (config('app.debug')) {
            $response['debug'] = [
                'timestamp' => now()->toISOString(),
                'environment' => config('app.env'),
            ];
        }

        return response()->json($response, $code);
    }

    /**
     * Create a validation error response
     */
    public static function validationError($errors, string $message = 'Validation failed'): JsonResponse
    {
        return self::error($message, 422, $errors);
    }

    /**
     * Create a not found response
     */
    public static function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return self::error($message, 404);
    }

    /**
     * Create an unauthorized response
     */
    public static function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return self::error($message, 401);
    }

    /**
     * Create a forbidden response
     */
    public static function forbidden(string $message = 'Forbidden'): JsonResponse
    {
        return self::error($message, 403);
    }

    /**
     * Create a server error response
     */
    public static function serverError(string $message = 'Internal server error'): JsonResponse
    {
        return self::error($message, 500);
    }
}
