<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $apiKey;
    private string $username;
    private string $from;
    private bool $enabled;

    private bool $demoMode;

    public function __construct()
    {
        $this->apiKey = config('services.africastalking.api_key');
        $this->username = config('services.africastalking.username');
        $this->from = config('services.africastalking.from', 'GFMS');
        $this->enabled = config('services.africastalking.enabled', false);
        $this->demoMode = config('services.sms.demo_mode', env('SMS_DEMO_MODE', false));
    }

    /**
     * Send SMS via Africa's Talking
     */
    public function send(string $phoneNumber, string $message): bool
    {
        // Demo Mode: Log but don't send (for presentations/testing)
        if ($this->demoMode) {
            Log::info("SMS (DEMO MODE - Not Sent): To: {$phoneNumber}, Message: {$message}");
            Log::info("💡 Use OTP Viewer at http://localhost:8000/otp-viewer.html to see OTP");
            return true;
        }

        // If SMS is disabled, just log it
        if (!$this->enabled) {
            Log::info("SMS (disabled): To: {$phoneNumber}, Message: {$message}");
            return true;
        }

        try {
            // Format phone number for Kenya (ensure it starts with +254)
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withHeaders([
                'apiKey' => $this->apiKey,
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Accept' => 'application/json',
            ])->asForm()->post('https://api.africastalking.com/version1/messaging', [
                'username' => $this->username,
                'to' => $formattedPhone,
                'message' => $message,
                'from' => $this->from,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                Log::info('SMS sent successfully', [
                    'phone' => $formattedPhone,
                    'response' => $data,
                ]);

                return true;
            }

            Log::error('SMS sending failed', [
                'phone' => $formattedPhone,
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('SMS sending exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Format phone number to international format
     * Handles: 0712345678, 712345678, +254712345678, 254712345678
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove spaces, dashes, and parentheses
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        // If starts with 0, replace with +254
        if (str_starts_with($phone, '0')) {
            return '+254' . substr($phone, 1);
        }

        // If starts with 254 (no +), add +
        if (str_starts_with($phone, '254')) {
            return '+' . $phone;
        }

        // If starts with 7 or 1 (Kenyan mobile), add +254
        if (preg_match('/^[71]/', $phone)) {
            return '+254' . $phone;
        }

        // Already in correct format or international
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Default: assume it's Kenyan and add +254
        return '+254' . $phone;
    }

    /**
     * Send OTP SMS
     */
    public function sendOtp(string $phoneNumber, string $otp): bool
    {
        $message = "Your GFMS verification code is: {$otp}. Valid for 5 minutes. Do not share this code with anyone.";
        return $this->send($phoneNumber, $message);
    }

    /**
     * Check if SMS service is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
