<?php

namespace App\Services;

use App\Exceptions\AccountLockedException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\InactiveAccountException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Facades\Activity;

class AuthService
{
    public function __construct(
        private UserRepository $userRepository,
        private OtpService $otpService
    ) {}

    /**
     * Attempt login with personal number and password
     * Returns user ID if successful, throws exception otherwise
     */
    public function attemptLogin(string $personalNumber, string $password, string $otpChannel = 'email'): array
    {
        // Find user by personal number
        $user = $this->userRepository->findByPersonalNumber($personalNumber);
        
        if (!$user) {
            // Log failed login attempt
            activity()
                ->withProperties([
                    'personal_number' => $personalNumber,
                    'reason' => 'Invalid personal number',
                    'ip_address' => request()->ip(),
                ])
                ->log('Failed login attempt');
            
            throw new AuthenticationException('Invalid personal number or password');
        }

        // Check if account is locked
        if ($user->isLocked()) {
            $lockedUntil = $user->locked_until->format('Y-m-d H:i:s');
            
            // Log locked account attempt
            activity()
                ->causedBy($user)
                ->withProperties([
                    'locked_until' => $lockedUntil,
                    'ip_address' => request()->ip(),
                ])
                ->log('Login attempt on locked account');
            
            throw new AccountLockedException("Account is locked until {$lockedUntil}");
        }

        // Check if account is active
        if (!$user->is_active) {
            // Log inactive account attempt
            activity()
                ->causedBy($user)
                ->withProperties(['ip_address' => request()->ip()])
                ->log('Login attempt on inactive account');
            
            throw new InactiveAccountException('Your account has been deactivated. Please contact your administrator.');
        }

        // Verify password
        if (!Hash::check($password, $user->password)) {
            $user->incrementFailedAttempts();
            
            // Log failed password attempt
            activity()
                ->causedBy($user)
                ->withProperties([
                    'failed_attempts' => $user->failed_login_attempts,
                    'ip_address' => request()->ip(),
                ])
                ->log('Failed password attempt');
            
            $remainingAttempts = 5 - $user->failed_login_attempts;
            if ($remainingAttempts > 0) {
                throw new AuthenticationException("Invalid personal number or password. {$remainingAttempts} attempts remaining.");
            } else {
                // Log account lockout
                activity()
                    ->causedBy($user)
                    ->withProperties([
                        'locked_until' => $user->locked_until,
                        'ip_address' => request()->ip(),
                    ])
                    ->log('Account locked due to failed attempts');
                
                throw new AccountLockedException('Account has been locked due to multiple failed login attempts. Please try again in 30 minutes.');
            }
        }

        // Password is correct, generate and send OTP
        $otp = $this->otpService->generate($user, $otpChannel);
        
        // Send OTP based on channel
        if ($otpChannel === 'email') {
            $this->otpService->sendEmail($user, $otp);
        } elseif ($otpChannel === 'sms') {
            $this->otpService->sendSms($user, $otp);
        }

        // Log OTP generation
        activity()
            ->causedBy($user)
            ->withProperties([
                'channel' => $otpChannel,
                'ip_address' => request()->ip(),
            ])
            ->log('OTP generated and sent');

        return [
            'user_id' => $user->id,
            'channel' => $otpChannel,
            'message' => $otpChannel === 'email' 
                ? "OTP sent to your email: {$user->email}" 
                : "OTP sent to your phone: {$user->phone}",
        ];
    }

    /**
     * Verify OTP and issue authentication token
     */
    public function verifyOtp(int $userId, string $code, string $otpChannel = 'email'): array
    {
        $user = $this->userRepository->findWithRolesAndPermissions($userId);
        
        if (!$user) {
            throw new AuthenticationException('User not found');
        }

        try {
            // Verify OTP
            $this->otpService->verify($user, $code, $otpChannel);
        } catch (\Exception $e) {
            // Log failed OTP verification
            activity()
                ->causedBy($user)
                ->withProperties([
                    'channel' => $otpChannel,
                    'reason' => $e->getMessage(),
                    'ip_address' => request()->ip(),
                ])
                ->log('Failed OTP verification');
            
            throw $e;
        }

        // OTP is valid, reset failed attempts and update last login
        $user->resetFailedAttempts();
        $user->update(['last_login_at' => now()]);

        // Generate Sanctum token
        $token = $user->createToken('auth-token')->plainTextToken;

        // Log successful login
        activity()
            ->causedBy($user)
            ->withProperties([
                'channel' => $otpChannel,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Successful login');

        // Return user with roles and permissions
        return [
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'personal_number' => $user->personal_number,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'organization' => $user->organization,
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
        ];
    }

    /**
     * Logout user by revoking current token
     */
    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
        
        // Log logout
        activity()
            ->causedBy($user)
            ->withProperties(['ip_address' => request()->ip()])
            ->log('User logged out');
    }

    /**
     * Get authenticated user with roles and permissions
     */
    public function me(User $user): array
    {
        $user->load(['roles.permissions', 'organization']);
        
        return [
            'id' => $user->id,
            'personal_number' => $user->personal_number,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'organization' => $user->organization,
            'roles' => $user->roles->pluck('name'),
            'permissions' => $user->getAllPermissions()->pluck('name'),
            'last_login_at' => $user->last_login_at,
        ];
    }
}
