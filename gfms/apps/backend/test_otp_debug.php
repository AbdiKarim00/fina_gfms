<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Services\OtpService;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Redis;

echo "=== OTP Verification Debug Test ===\n\n";

try {
    // Test 1: Check Redis connection
    echo "1. Testing Redis connection...\n";
    Redis::set('test_key', 'test_value');
    $value = Redis::get('test_key');
    Redis::del('test_key');
    echo "   Redis: OK (set/get/del test passed)\n\n";

    // Test 2: Get a test user
    echo "2. Getting test user...\n";
    $user = User::first();
    if (!$user) {
        echo "   ERROR: No users found in database\n";
        exit(1);
    }
    echo "   Found user: {$user->name} (ID: {$user->id})\n";
    echo "   Email: {$user->email}\n";
    echo "   Phone: {$user->phone}\n\n";

    // Test 3: Generate OTP
    echo "3. Testing OTP generation...\n";
    $otpService = new OtpService();
    $otp = $otpService->generate($user, 'email');
    echo "   Generated OTP: {$otp}\n";
    
    // Check Redis storage
    $redisKey = "otp:email:{$user->id}";
    $storedOtp = Redis::get($redisKey);
    echo "   OTP stored in Redis key '{$redisKey}': {$storedOtp}\n";
    echo "   TTL: " . Redis::ttl($redisKey) . " seconds\n\n";

    // Test 4: Verify correct OTP
    echo "4. Testing OTP verification (correct code)...\n";
    try {
        $result = $otpService->verify($user, $otp, 'email');
        echo "   Verification: SUCCESS\n";
    } catch (\Exception $e) {
        echo "   Verification FAILED: " . $e->getMessage() . "\n";
        echo "   Exception class: " . get_class($e) . "\n";
    }

    // Generate new OTP for next test
    $otp = $otpService->generate($user, 'email');
    echo "\n   Generated new OTP: {$otp}\n";

    // Test 5: Verify incorrect OTP
    echo "5. Testing OTP verification (incorrect code)...\n";
    try {
        $result = $otpService->verify($user, '000000', 'email');
        echo "   Verification: UNEXPECTED SUCCESS\n";
    } catch (\Exception $e) {
        echo "   Verification FAILED as expected: " . $e->getMessage() . "\n";
        echo "   Exception class: " . get_class($e) . "\n";
    }

    // Test 6: Test AuthService OTP verification
    echo "\n6. Testing AuthService OTP verification...\n";
    $authService = app(AuthService::class);
    $otp = $otpService->generate($user, 'email');
    echo "   Generated OTP for AuthService test: {$otp}\n";
    
    try {
        $result = $authService->verifyOtp($user->id, $otp, 'email');
        echo "   AuthService verification: SUCCESS\n";
        echo "   Token generated: " . substr($result['token'], 0, 20) . "...\n";
    } catch (\Exception $e) {
        echo "   AuthService verification FAILED: " . $e->getMessage() . "\n";
        echo "   Exception class: " . get_class($e) . "\n";
        echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "   Trace:\n";
        echo implode("\n", array_slice(explode("\n", $e->getTraceAsString()), 0, 5));
    }

    echo "\n=== Test Complete ===\n";

} catch (\Exception $e) {
    echo "CRITICAL ERROR: " . $e->getMessage() . "\n";
    echo "Exception class: " . get_class($e) . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
