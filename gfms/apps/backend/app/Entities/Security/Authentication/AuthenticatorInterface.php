<?php

namespace App\Entities\Security\Authentication;

/**
 * Authenticator Interface
 *
 * Defines the contract for authentication mechanisms in the fleet management system.
 */
interface AuthenticatorInterface
{
    /**
     * Authenticate a user with their credentials
     *
     * @param  string  $identifier  User identifier (personal number, email, etc.)
     * @param  string  $password  User password
     * @return bool True if authentication is successful, false otherwise
     */
    public function authenticate(string $identifier, string $password): bool;

    /**
     * Verify multi-factor authentication token
     *
     * @param  string  $token  The MFA token provided by the user
     * @return bool True if token is valid, false otherwise
     */
    public function verifyMfaToken(string $token): bool;

    /**
     * Generate a new authentication token for the user
     *
     * @param  int  $userId  The ID of the authenticated user
     * @return string The generated authentication token
     */
    public function generateToken(int $userId): string;

    /**
     * Validate an existing authentication token
     *
     * @param  string  $token  The token to validate
     * @return bool True if token is valid, false otherwise
     */
    public function validateToken(string $token): bool;

    /**
     * Logout a user and invalidate their session
     *
     * @param  int  $userId  The ID of the user to logout
     * @return bool True if logout is successful, false otherwise
     */
    public function logout(int $userId): bool;

    /**
     * Check if a user account is locked
     *
     * @param  int  $userId  The ID of the user to check
     * @return bool True if account is locked, false otherwise
     */
    public function isAccountLocked(int $userId): bool;

    /**
     * Record a failed login attempt
     *
     * @param  int  $userId  The ID of the user who failed to login
     */
    public function recordFailedAttempt(int $userId): void;

    /**
     * Reset failed login attempts for a user
     *
     * @param  int  $userId  The ID of the user to reset
     */
    public function resetFailedAttempts(int $userId): void;
}
