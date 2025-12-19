<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioSmsService
{
    private string $accountSid;

    private string $authToken;

    private string $from;

    private bool $enabled;

    public function __construct()
    {
        $this->accountSid = config('services.twilio.sid');
        $this->authToken = config('services.twilio.token');
        $this->from = config('services.twilio.from');
        $this->enabled = config('services.twilio.enabled', false);
    }

    /**
     * Send SMS via Twilio
     */
    public function send(string $phoneNumber, string $message): bool
    {
        if (! $this->enabled) {
            Log::info("SMS (Twilio disabled): To: {$phoneNumber}, Message: {$message}");

            return true;
        }

        try {
            $formattedPhone = $this->formatPhoneNumber($phoneNumber);

            $response = Http::withBasicAuth($this->accountSid, $this->authToken)
                ->asForm()
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json", [
                    'To' => $formattedPhone,
                    'From' => $this->from,
                    'Body' => $message,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                Log::info('Twilio SMS sent successfully', [
                    'phone' => $formattedPhone,
                    'sid' => $data['sid'] ?? null,
                    'status' => $data['status'] ?? null,
                ]);

                return true;
            }

            Log::error('Twilio SMS sending failed', [
                'phone' => $formattedPhone,
                'status' => $response->status(),
                'error' => $response->json(),
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Twilio SMS exception', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Format phone number to E.164 format
     */
    private function formatPhoneNumber(string $phone): string
    {
        $phone = preg_replace('/[\s\-\(\)]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            return '+254'.substr($phone, 1);
        }

        if (str_starts_with($phone, '254')) {
            return '+'.$phone;
        }

        if (preg_match('/^[71]/', $phone)) {
            return '+254'.$phone;
        }

        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        return '+254'.$phone;
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
     * Check if Twilio is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
}
