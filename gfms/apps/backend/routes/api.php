<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

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

        // Vehicle routes with permission-based access
        Route::prefix('vehicles')->group(function () {
            // Statistics endpoint (view permission)
            Route::get('/statistics', [App\Http\Controllers\VehicleController::class, 'statistics'])
                ->middleware('permission:view_vehicles');
            
            // Bulk operations
            Route::post('/bulk-update', [App\Http\Controllers\VehicleController::class, 'bulkUpdate'])
                ->middleware('permission:edit_vehicles');
            Route::post('/bulk-delete', [App\Http\Controllers\VehicleController::class, 'bulkDelete'])
                ->middleware('permission:delete_vehicles');
        });

        // RESTful vehicle routes
        Route::apiResource('vehicles', App\Http\Controllers\VehicleController::class)
            ->middleware([
                'index' => 'permission:view_vehicles',
                'show' => 'permission:view_vehicles',
                'store' => 'permission:create_vehicles',
                'update' => 'permission:edit_vehicles',
                'destroy' => 'permission:delete_vehicles',
            ]);

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
    });
});