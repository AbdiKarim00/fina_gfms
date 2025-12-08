<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Configuration
    |--------------------------------------------------------------------------
    |
    | Choose your SMS provider: 'africastalking' or 'twilio'
    | Africa's Talking: Cheaper for Kenya (KES 0.80/SMS)
    | Twilio: Global reach but expensive ($0.05/SMS)
    |
    | Demo Mode: When true, OTPs are generated but SMS not sent
    | Perfect for demos, presentations, and testing
    | Use OTP Viewer (http://localhost:8000/otp-viewer.html) to see OTPs
    |
    */
    'sms' => [
        'provider' => env('SMS_PROVIDER', 'africastalking'), // 'africastalking' or 'twilio'
        'demo_mode' => env('SMS_DEMO_MODE', false), // true = demo, false = production
    ],

    /*
    |--------------------------------------------------------------------------
    | Africa's Talking SMS Service
    |--------------------------------------------------------------------------
    |
    | Configuration for Africa's Talking SMS service.
    | Sign up at: https://africastalking.com
    | Get sandbox credentials for testing
    |
    */
    'africastalking' => [
        'username' => env('AFRICASTALKING_USERNAME', 'sandbox'),
        'api_key' => env('AFRICASTALKING_API_KEY'),
        'from' => env('AFRICASTALKING_FROM', 'GFMS'),
        'enabled' => env('AFRICASTALKING_ENABLED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Twilio SMS Service (Alternative)
    |--------------------------------------------------------------------------
    |
    | Configuration for Twilio SMS service.
    | Sign up at: https://www.twilio.com
    | Get $15 free trial credit
    |
    */
    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'token' => env('TWILIO_TOKEN'),
        'from' => env('TWILIO_FROM'),
        'enabled' => env('TWILIO_ENABLED', false),
    ],

];
