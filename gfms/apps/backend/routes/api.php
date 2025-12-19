<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// Health check endpoint
Route::get('/health', function () {
    try {
        DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Exception $e) {
        $dbStatus = 'disconnected';
    }

    return response()->json([
        'status' => 'healthy',
        'service' => 'GFMS Backend API',
        'version' => '1.0.0',
        'timestamp' => now()->toIso8601String(),
        'database' => $dbStatus,
        'environment' => app()->environment(),
    ]);
});

// API version prefix
Route::prefix('v1')->group(function () {

    // Public routes
    Route::get('/ping', function () {
        return response()->json(['message' => 'pong']);
    });

    // Development Tools (only in debug mode)
    Route::prefix('dev')->group(function () {
        Route::get('/otps', [App\Http\Controllers\DevToolsController::class, 'getOtps']);
        Route::get('/otps/{userId}/{channel?}', [App\Http\Controllers\DevToolsController::class, 'getUserOtp']);
        Route::get('/sms-logs', [App\Http\Controllers\DevToolsController::class, 'getSmsLogs']);
        Route::get('/activity-logs', [App\Http\Controllers\DevToolsController::class, 'getActivityLogs']);
    });

    // Authentication routes (public) with rate limiting
    Route::middleware('throttle:5,15')->group(function () {
        Route::post('/auth/login', [App\Http\Controllers\AuthController::class, 'login']);
        Route::post('/auth/verify-otp', [App\Http\Controllers\AuthController::class, 'verifyOtp']);
    });

    // Protected routes
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/auth/me', [App\Http\Controllers\AuthController::class, 'me']);
        Route::post('/auth/logout', [App\Http\Controllers\AuthController::class, 'logout']);

        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Example: Permission-based routes
        Route::middleware('permission:view_vehicles')->group(function () {
            Route::get('/vehicles', function () {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle list (requires view_vehicles permission)',
                    'data' => [],
                ]);
            });
        });

        Route::middleware('permission:create_vehicles')->group(function () {
            Route::post('/vehicles', function () {
                return response()->json([
                    'success' => true,
                    'message' => 'Vehicle created (requires create_vehicles permission)',
                ]);
            });
        });

        // Example: Role-based routes
        Route::middleware('role:Admin')->group(function () {
            Route::get('/admin/dashboard', function () {
                return response()->json([
                    'success' => true,
                    'message' => 'Admin dashboard (requires Admin role)',
                ]);
            });
        });

        Route::middleware('role:Super Admin')->group(function () {
            Route::get('/admin/system-settings', function () {
                return response()->json([
                    'success' => true,
                    'message' => 'System settings (requires Super Admin role)',
                ]);
            });
        });

        // Cabinet Secretary routes
        Route::middleware('role:Cabinet Secretary')->prefix('cabinet-secretary')->group(function () {
            Route::get('/policy-compliance', [App\Http\Controllers\CabinetSecretaryController::class, 'getPolicyCompliance']);
            Route::get('/budget-oversight', [App\Http\Controllers\CabinetSecretaryController::class, 'getBudgetOversight']);
            Route::post('/interventions', [App\Http\Controllers\CabinetSecretaryController::class, 'postInterventions']);
            Route::get('/strategic-performance', [App\Http\Controllers\CabinetSecretaryController::class, 'getStrategicPerformance']);
        });
    });
});
