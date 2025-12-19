<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestConfiguration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-configuration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test configuration management implementation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing configuration management implementation...');

        // Test GFMS configuration values
        $appName = config('gfms.app.name');
        $this->info('App Name: '.$appName);

        $authMaxAttempts = config('gfms.auth.max_login_attempts');
        $this->info('Max Login Attempts: '.$authMaxAttempts);

        $otpExpiry = config('gfms.auth.otp_expiry_minutes');
        $this->info('OTP Expiry Minutes: '.$otpExpiry);

        $smsProvider = config('gfms.sms.provider');
        $this->info('SMS Provider: '.$smsProvider);

        $defaultFuelType = config('gfms.fleet.default_fuel_type');
        $this->info('Default Fuel Type: '.$defaultFuelType);

        // Test environment variable fallback
        $testValue = config('gfms.nonexistent.value', 'default_value');
        $this->info('Fallback Test: '.$testValue);

        $this->info('Configuration management test completed successfully!');
    }
}
