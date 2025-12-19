<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for GFMS application settings
    |
    */

    'app' => [
        'name' => env('APP_NAME', 'GFMS'),
        'version' => env('APP_VERSION', '1.0.0'),
        'frontend_url' => env('FRONTEND_URL', 'http://localhost:3000'),
        'mobile_app_name' => env('MOBILE_APP_NAME', 'GFMS Mobile'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for authentication and security settings
    |
    */

    'auth' => [
        'max_login_attempts' => env('MAX_LOGIN_ATTEMPTS', 5),
        'lockout_duration_minutes' => env('LOCKOUT_DURATION_MINUTES', 30),
        'otp_expiry_minutes' => env('OTP_EXPIRY_MINUTES', 5),
        'otp_max_attempts' => env('OTP_MAX_ATTEMPTS', 3),
        'jwt_ttl' => env('JWT_TTL', 1440), // in minutes
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for SMS services
    |
    */

    'sms' => [
        'provider' => env('SMS_PROVIDER', 'africastalking'),
        'demo_mode' => env('SMS_DEMO_MODE', false),
        'default_from' => env('SMS_FROM', 'GFMS'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Fleet Management Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for fleet management operations
    |
    */

    'fleet' => [
        'default_fuel_type' => env('DEFAULT_FUEL_TYPE', 'petrol'),
        'maintenance_reminder_days' => env('MAINTENANCE_REMINDER_DAYS', 7),
        'insurance_expiry_warning_days' => env('INSURANCE_EXPIRY_WARNING_DAYS', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Notification Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for notification services
    |
    */

    'notifications' => [
        'email_from_address' => env('MAIL_FROM_ADDRESS', 'no-reply@gfms.go.ke'),
        'email_from_name' => env('MAIL_FROM_NAME', 'GFMS System'),
        'enable_slack_notifications' => env('ENABLE_SLACK_NOTIFICATIONS', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for application logging
    |
    */

    'logging' => [
        'activity_log_enabled' => env('ACTIVITY_LOG_ENABLED', true),
        'log_level' => env('LOG_LEVEL', 'debug'),
        'max_log_files' => env('MAX_LOG_FILES', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | External Services
    |--------------------------------------------------------------------------
    |
    | Configuration for external service integrations
    |
    */

    'external' => [
        'ntsa' => [
            'api_url' => env('NTSA_API_URL'),
            'api_key' => env('NTSA_API_KEY'),
            'enabled' => env('NTSA_ENABLED', false),
        ],
        'ifmis' => [
            'api_url' => env('IFMIS_API_URL'),
            'api_key' => env('IFMIS_API_KEY'),
            'enabled' => env('IFMIS_ENABLED', false),
        ],
        'cmte' => [
            'api_url' => env('CMTE_API_URL'),
            'api_key' => env('CMTE_API_KEY'),
            'enabled' => env('CMTE_ENABLED', false),
        ],
    ],
];
