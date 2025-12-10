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

        // Booking routes with permission-based access
        Route::prefix('bookings')->group(function () {
            // My bookings (Transport Officer, Driver)
            Route::get('/my-bookings', [App\Http\Controllers\BookingController::class, 'myBookings'])
                ->middleware('permission:view_bookings');
            
            // Pending approvals (Fleet Manager, Admin)
            Route::get('/pending', [App\Http\Controllers\BookingController::class, 'pending'])
                ->middleware('permission:approve_bookings');
            
            // Calendar view
            Route::get('/calendar', [App\Http\Controllers\BookingController::class, 'calendar'])
                ->middleware('permission:view_bookings');
            
            // Check conflicts
            Route::post('/check-conflicts', [App\Http\Controllers\BookingController::class, 'checkConflicts'])
                ->middleware('permission:view_bookings');
            
            // Available vehicles
            Route::post('/available-vehicles', [App\Http\Controllers\BookingController::class, 'availableVehicles'])
                ->middleware('permission:view_bookings');
            
            // Statistics
            Route::get('/statistics', [App\Http\Controllers\BookingController::class, 'statistics'])
                ->middleware('permission:view_bookings');
            
            // Bulk approve
            Route::post('/bulk-approve', [App\Http\Controllers\BookingController::class, 'bulkApprove'])
                ->middleware('permission:approve_bookings');
            
            // Approve/Reject specific booking
            Route::post('/{booking}/approve', [App\Http\Controllers\BookingController::class, 'approve'])
                ->middleware('permission:approve_bookings');
            Route::post('/{booking}/reject', [App\Http\Controllers\BookingController::class, 'reject'])
                ->middleware('permission:approve_bookings');
            Route::post('/{booking}/cancel', [App\Http\Controllers\BookingController::class, 'cancel'])
                ->middleware('permission:view_bookings');
        });

        // RESTful booking routes - index and show
        Route::get('/bookings', [App\Http\Controllers\BookingController::class, 'index'])
            ->middleware('permission:view_bookings');
        Route::get('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'show'])
            ->middleware('permission:view_bookings');
        Route::post('/bookings', [App\Http\Controllers\BookingController::class, 'store'])
            ->middleware('permission:create_bookings');
        Route::put('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'update'])
            ->middleware('permission:create_bookings');
        Route::delete('/bookings/{booking}', [App\Http\Controllers\BookingController::class, 'destroy'])
            ->middleware('permission:create_bookings');

        // Conflict Resolution routes
        Route::prefix('conflict-resolution')->group(function () {
            Route::post('/suggestions', [App\Http\Controllers\ConflictResolutionController::class, 'getSuggestions'])
                ->middleware('permission:view_bookings');
            Route::post('/alternative-vehicles', [App\Http\Controllers\ConflictResolutionController::class, 'getAlternativeVehicles'])
                ->middleware('permission:view_bookings');
            Route::post('/alternative-times', [App\Http\Controllers\ConflictResolutionController::class, 'getAlternativeTimeSlots'])
                ->middleware('permission:view_bookings');
            Route::post('/alternative-drivers', [App\Http\Controllers\ConflictResolutionController::class, 'getAlternativeDrivers'])
                ->middleware('permission:view_bookings');
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
    });
});