<?php

namespace App\Services\Contracts;

use App\Models\User;

interface AuthenticationServiceInterface
{
    /**
     * Attempt login with personal number and password
     * Returns user ID if successful, throws exception otherwise
     */
    public function attemptLogin(string $personalNumber, string $password, string $otpChannel = 'email'): array;

    /**
     * Verify OTP and issue authentication token
     */
    public function verifyOtp(int $userId, string $code, string $otpChannel = 'email'): array;

    /**
     * Logout user by revoking current token
     */
    public function logout(User $user): void;

    /**
     * Get authenticated user with roles and permissions
     */
    public function me(User $user): array;

    /**
     * Validate user's organizational hierarchy
     */
    public function validateOrganizationalHierarchy(User $user): bool;

    /**
     * Get user's subordinates based on organizational hierarchy
     */
    public function getUserSubordinates(User $user): array;
}
