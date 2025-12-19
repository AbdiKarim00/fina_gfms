<?php

namespace App\Services\Contracts;

use App\Models\User;

interface OrganizationalValidationServiceInterface
{
    /**
     * Validate user's organizational hierarchy
     */
    public function validateHierarchy(User $user): bool;

    /**
     * Get user's subordinates based on organizational hierarchy
     */
    public function getUserSubordinates(User $user): array;

    /**
     * Check if organization is part of national government
     */
    public function isNationalOrganization(User $user): bool;

    /**
     * Check if organization is a county
     */
    public function isCountyOrganization(User $user): bool;
}
