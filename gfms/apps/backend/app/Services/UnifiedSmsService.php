<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class UnifiedSmsService
{
    private string $provider;

    private $smsService;

    public function __construct()
    {
        // Choose provider based on config
        $this->provider = config('services.sms.provider', 'africastalking');

        $this->smsService = match ($this->provider) {
            'twilio' => app(TwilioSmsService::class),
            'africastalking' => app(SmsService::class),
            default => app(SmsService::class),
        };

        Log::info("SMS Provider: {$this->provider}");
    }

    /**
     * Send SMS using configured provider
     */
    public function send(string $phoneNumber, string $message): bool
    {
        return $this->smsService->send($phoneNumber, $message);
    }

    /**
     * Send OTP SMS
     */
    public function sendOtp(string $phoneNumber, string $otp): bool
    {
        return $this->smsService->sendOtp($phoneNumber, $otp);
    }

    /**
     * Check if SMS is enabled
     */
    public function isEnabled(): bool
    {
        return $this->smsService->isEnabled();
    }

    /**
     * Get current provider
     */
    public function getProvider(): string
    {
        return $this->provider;
    }
}
