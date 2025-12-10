<?php

namespace App\Services;

use App\Exceptions\InvalidOtpException;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class OtpService
{
    // Development: More lenient settings for easier testing
    // Production: Strict security settings
    private const OTP_EXPIRY_MINUTES = 5;
    private const OTP_EXPIRY_MINUTES_DEV = 15; // 15 minutes in dev
    private const MAX_OTP_ATTEMPTS = 3;
    private const MAX_OTP_ATTEMPTS_DEV = 10; // 10 attempts in dev
    private const OTP_LENGTH = 6;

    /**
     * Get OTP expiry time based on environment
     */
    private function getOtpExpiryMinutes(): int
    {
        return config('app.env') === 'production' 
            ? self::OTP_EXPIRY_MINUTES 
            : self::OTP_EXPIRY_MINUTES_DEV;
    }

    /**
     * Get max OTP attempts based on environment
     */
    private function getMaxOtpAttempts(): int
    {
        return config('app.env') === 'production' 
            ? self::MAX_OTP_ATTEMPTS 
            : self::MAX_OTP_ATTEMPTS_DEV;
    }

    /**
     * Generate a 6-digit OTP and store in Redis
     */
    public function generate(User $user, string $channel = 'email'): string
    {
        $otp = str_pad((string) random_int(0, 999999), self::OTP_LENGTH, '0', STR_PAD_LEFT);
        
        $key = $this->getRedisKey($user->id, $channel);
        $attemptsKey = $this->getAttemptsKey($user->id, $channel);
        
        $expiryMinutes = $this->getOtpExpiryMinutes();
        
        // Store OTP with expiry
        Redis::setex($key, $expiryMinutes * 60, $otp);
        
        // Reset attempts counter
        Redis::del($attemptsKey);
        
        return $otp;
    }

    /**
     * Verify OTP code
     */
    public function verify(User $user, string $code, string $channel = 'email'): bool
    {
        $key = $this->getRedisKey($user->id, $channel);
        $attemptsKey = $this->getAttemptsKey($user->id, $channel);
        
        $maxAttempts = $this->getMaxOtpAttempts();
        $expiryMinutes = $this->getOtpExpiryMinutes();
        
        // Check if OTP exists
        $storedOtp = Redis::get($key);
        if (!$storedOtp) {
            throw new InvalidOtpException('OTP has expired or does not exist');
        }
        
        // Check attempts
        $attempts = (int) Redis::get($attemptsKey);
        if ($attempts >= $maxAttempts) {
            Redis::del($key);
            Redis::del($attemptsKey);
            $remainingAttempts = 0;
            throw new InvalidOtpException('Maximum OTP verification attempts exceeded');
        }
        
        // Verify OTP
        if ($storedOtp !== $code) {
            Redis::incr($attemptsKey);
            Redis::expire($attemptsKey, $expiryMinutes * 60);
            $remainingAttempts = $maxAttempts - ($attempts + 1);
            throw new InvalidOtpException("Invalid OTP code. {$remainingAttempts} attempts remaining.");
        }
        
        // OTP is valid, clean up
        Redis::del($key);
        Redis::del($attemptsKey);
        
        return true;
    }

    /**
     * Send OTP via email
     */
    public function sendEmail(User $user, string $otp): void
    {
        Mail::to($user->email)->queue(new OtpMail($user, $otp));
    }

    /**
     * Send OTP via SMS
     */
    public function sendSms(User $user, string $otp): void
    {
        $smsService = app(SmsService::class);
        $smsService->sendOtp($user->phone, $otp);
    }

    /**
     * Get Redis key for OTP storage
     */
    private function getRedisKey(int $userId, string $channel): string
    {
        return "otp:{$channel}:{$userId}";
    }

    /**
     * Get Redis key for attempts counter
     */
    private function getAttemptsKey(int $userId, string $channel): string
    {
        return "otp_attempts:{$channel}:{$userId}";
    }

    /**
     * Get remaining time for OTP in seconds
     */
    public function getRemainingTime(User $user, string $channel = 'email'): int
    {
        $key = $this->getRedisKey($user->id, $channel);
        return (int) Redis::ttl($key);
    }
}
