<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\OtpService;
use App\Services\AuthService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Redis;
use Tests\TestCase;

class OtpVerificationTest extends TestCase
{
    use RefreshDatabase;

    private OtpService $otpService;
    private AuthService $authService;
    private User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->otpService = app(OtpService::class);
        $this->authService = app(AuthService::class);
        $this->testUser = User::factory()->create([
            'email' => 'test@example.com',
            'phone' => '+254712345678',
            'password' => bcrypt('password123'),
            'is_active' => true,
        ]);
    }

    protected function tearDown(): void
    {
        // Clean up Redis keys after each test
        Redis::del("otp:email:{$this->testUser->id}");
        Redis::del("otp:sms:{$this->testUser->id}");
        Redis::del("otp_attempts:email:{$this->testUser->id}");
        Redis::del("otp_attempts:sms:{$this->testUser->id}");
        parent::tearDown();
    }

    /** @test */
    public function it_can_generate_otp_for_email_channel()
    {
        $otp = $this->otpService->generate($this->testUser, 'email');

        $this->assertIsString($otp);
        $this->assertEquals(6, strlen($otp));
        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp);

        // Verify OTP is stored in Redis
        $redisKey = "otp:email:{$this->testUser->id}";
        $storedOtp = Redis::get($redisKey);
        $this->assertEquals($otp, $storedOtp);
        $this->assertGreaterThan(0, Redis::ttl($redisKey));
    }

    /** @test */
    public function it_can_generate_otp_for_sms_channel()
    {
        $otp = $this->otpService->generate($this->testUser, 'sms');

        $this->assertIsString($otp);
        $this->assertEquals(6, strlen($otp));

        // Verify OTP is stored in Redis
        $redisKey = "otp:sms:{$this->testUser->id}";
        $storedOtp = Redis::get($redisKey);
        $this->assertEquals($otp, $storedOtp);
    }

    /** @test */
    public function it_can_verify_correct_otp()
    {
        $otp = $this->otpService->generate($this->testUser, 'email');

        $result = $this->otpService->verify($this->testUser, $otp, 'email');

        $this->assertTrue($result);

        // Verify OTP is deleted after successful verification
        $redisKey = "otp:email:{$this->testUser->id}";
        $this->assertNull(Redis::get($redisKey));
    }

    /** @test */
    public function it_fails_verification_with_incorrect_otp()
    {
        $this->otpService->generate($this->testUser, 'email');

        $this->expectException(\App\Exceptions\InvalidOtpException::class);
        $this->expectExceptionMessage('Invalid OTP code');

        $this->otpService->verify($this->testUser, '000000', 'email');
    }

    /** @test */
    public function it_fails_verification_with_expired_otp()
    {
        $otp = $this->otpService->generate($this->testUser, 'email');

        // Manually expire the OTP
        $redisKey = "otp:email:{$this->testUser->id}";
        Redis::expire($redisKey, 1);
        sleep(2);

        $this->expectException(\App\Exceptions\InvalidOtpException::class);
        $this->expectExceptionMessage('OTP has expired or does not exist');

        $this->otpService->verify($this->testUser, $otp, 'email');
    }

    /** @test */
    public function it_limits_maximum_otp_attempts()
    {
        $this->otpService->generate($this->testUser, 'email');

        // Make maximum attempts
        for ($i = 0; $i < 3; $i++) {
            try {
                $this->otpService->verify($this->testUser, '000000', 'email');
            } catch (\App\Exceptions\InvalidOtpException $e) {
                if ($i < 2) {
                    $this->assertEquals('Invalid OTP code', $e->getMessage());
                }
            }
        }

        // The next attempt should fail with max attempts error
        $this->expectException(\App\Exceptions\InvalidOtpException::class);
        $this->expectExceptionMessage('Maximum OTP verification attempts exceeded');

        $this->otpService->verify($this->testUser, '000000', 'email');
    }

    /** @test */
    public function it_resets_attempts_counter_after_successful_verification()
    {
        $otp = $this->otpService->generate($this->testUser, 'email');

        // Make a failed attempt
        try {
            $this->otpService->verify($this->testUser, '000000', 'email');
        } catch (\App\Exceptions\InvalidOtpException $e) {
            // Expected
        }

        // Verify attempts counter is incremented
        $attemptsKey = "otp_attempts:email:{$this->testUser->id}";
        $this->assertEquals('1', Redis::get($attemptsKey));

        // Now verify correct OTP
        $result = $this->otpService->verify($this->testUser, $otp, 'email');
        $this->assertTrue($result);

        // Verify attempts counter is deleted
        $this->assertNull(Redis::get($attemptsKey));
    }

    /** @test */
    public function auth_service_can_verify_otp_and_generate_token()
    {
        // First attempt login to generate OTP
        $loginResult = $this->authService->attemptLogin(
            $this->testUser->personal_number,
            'password123',
            'email'
        );

        $this->assertArrayHasKey('user_id', $loginResult);
        $this->assertEquals($this->testUser->id, $loginResult['user_id']);

        // Extract OTP from Redis (in real scenario, user would receive it via email)
        $redisKey = "otp:email:{$this->testUser->id}";
        $otp = Redis::get($redisKey);

        // Verify OTP and get token
        $result = $this->authService->verifyOtp($this->testUser->id, $otp, 'email');

        $this->assertArrayHasKey('token', $result);
        $this->assertArrayHasKey('user', $result);
        $this->assertIsString($result['token']);
        $this->assertEquals($this->testUser->email, $result['user']['email']);
    }

    /** @test */
    public function it_returns_remaining_time_for_otp()
    {
        $this->otpService->generate($this->testUser, 'email');

        $remainingTime = $this->otpService->getRemainingTime($this->testUser, 'email');

        $this->assertIsInt($remainingTime);
        $this->assertGreaterThan(250, $remainingTime); // Should be close to 300 seconds (5 minutes)
        $this->assertLessThanOrEqual(300, $remainingTime);
    }

    /** @test */
    public function it_handles_nonexistent_otp_gracefully()
    {
        $this->expectException(\App\Exceptions\InvalidOtpException::class);
        $this->expectExceptionMessage('OTP has expired or does not exist');

        $this->otpService->verify($this->testUser, '123456', 'email');
    }

    /** @test */
    public function it_generates_different_otps_for_multiple_requests()
    {
        $otp1 = $this->otpService->generate($this->testUser, 'email');
        $otp2 = $this->otpService->generate($this->testUser, 'email');

        $this->assertNotEquals($otp1, $otp2);
    }

    /** @test */
    public function it_handles_email_and_sms_channels_independently()
    {
        $emailOtp = $this->otpService->generate($this->testUser, 'email');
        $smsOtp = $this->otpService->generate($this->testUser, 'sms');

        $this->assertNotEquals($emailOtp, $smsOtp);

        // Verify email OTP works independently
        $this->assertTrue($this->otpService->verify($this->testUser, $emailOtp, 'email'));

        // SMS OTP should still be valid
        $this->assertTrue($this->otpService->verify($this->testUser, $smsOtp, 'sms'));
    }
}
