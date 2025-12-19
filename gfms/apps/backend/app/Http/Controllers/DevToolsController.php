<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Redis;

class DevToolsController extends Controller
{
    /**
     * Get all OTPs from Redis (for development/testing only)
     */
    public function getOtps(): JsonResponse
    {
        if (! config('app.debug')) {
            return response()->json([
                'error' => 'This endpoint is only available in debug mode',
            ], 403);
        }

        $otps = [];

        // Get all OTP keys from Redis
        $keys = Redis::keys('*otp:*');

        foreach ($keys as $key) {
            // Remove the prefix that Laravel adds
            $cleanKey = str_replace(config('database.redis.options.prefix'), '', $key);

            // Get the OTP value
            $otp = Redis::get($cleanKey);

            // Get TTL (time to live)
            $ttl = Redis::ttl($cleanKey);

            // Parse the key to get channel and user_id
            // Format: otp:email:2 or otp:sms:2
            preg_match('/otp:(email|sms):(\d+)/', $cleanKey, $matches);

            if (count($matches) === 3) {
                $otps[] = [
                    'channel' => $matches[1],
                    'user_id' => (int) $matches[2],
                    'otp' => $otp,
                    'expires_in_seconds' => $ttl,
                    'expires_in_minutes' => round($ttl / 60, 1),
                ];
            }
        }

        return response()->json([
            'success' => true,
            'count' => count($otps),
            'otps' => $otps,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get OTP for specific user
     */
    public function getUserOtp(int $userId, string $channel = 'email'): JsonResponse
    {
        if (! config('app.debug')) {
            return response()->json([
                'error' => 'This endpoint is only available in debug mode',
            ], 403);
        }

        $key = "otp:{$channel}:{$userId}";
        $otp = Redis::get($key);
        $ttl = Redis::ttl($key);

        if (! $otp) {
            return response()->json([
                'success' => false,
                'message' => 'No OTP found for this user',
                'user_id' => $userId,
                'channel' => $channel,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'user_id' => $userId,
            'channel' => $channel,
            'otp' => $otp,
            'expires_in_seconds' => $ttl,
            'expires_in_minutes' => round($ttl / 60, 1),
        ]);
    }

    /**
     * Get recent SMS logs
     */
    public function getSmsLogs(): JsonResponse
    {
        if (! config('app.debug')) {
            return response()->json([
                'error' => 'This endpoint is only available in debug mode',
            ], 403);
        }

        $logFile = storage_path('logs/laravel.log');

        if (! file_exists($logFile)) {
            return response()->json([
                'success' => false,
                'message' => 'Log file not found',
            ], 404);
        }

        // Read last 1000 lines
        $lines = [];
        $file = new \SplFileObject($logFile, 'r');
        $file->seek(PHP_INT_MAX);
        $lastLine = $file->key();
        $startLine = max(0, $lastLine - 1000);

        $file->seek($startLine);
        while (! $file->eof()) {
            $lines[] = $file->current();
            $file->next();
        }

        // Filter for SMS-related logs
        $smsLogs = [];
        foreach ($lines as $line) {
            if (stripos($line, 'SMS') !== false || stripos($line, 'africastalking') !== false) {
                $smsLogs[] = trim($line);
            }
        }

        return response()->json([
            'success' => true,
            'count' => count($smsLogs),
            'logs' => array_slice($smsLogs, -50), // Last 50 SMS logs
        ]);
    }

    /**
     * Get activity logs
     */
    public function getActivityLogs(): JsonResponse
    {
        if (! config('app.debug')) {
            return response()->json([
                'error' => 'This endpoint is only available in debug mode',
            ], 403);
        }

        $activities = \Spatie\Activitylog\Models\Activity::with('causer')
            ->latest()
            ->limit(100)
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'description' => $activity->description,
                    'causer' => $activity->causer ? [
                        'id' => $activity->causer->id,
                        'name' => $activity->causer->name,
                        'personal_number' => $activity->causer->personal_number,
                    ] : null,
                    'properties' => $activity->properties,
                    'created_at' => $activity->created_at->toIso8601String(),
                ];
            });

        return response()->json([
            'success' => true,
            'count' => $activities->count(),
            'activities' => $activities,
        ]);
    }
}
