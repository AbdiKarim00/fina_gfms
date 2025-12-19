<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\OrganizationalValidationServiceInterface;

class OrganizationalValidationService implements OrganizationalValidationServiceInterface
{
    /**
     * Validate user's organizational hierarchy
     */
    public function validateHierarchy(User $user): bool
    {
        // Check if user's organization is active
        if (! $user->organization || ! $user->organization->is_active) {
            return false;
        }

        // Check if user's organization is part of the approved hierarchy
        if (! in_array($user->organization->type, ['ministry', 'department', 'agency', 'county'])) {
            return false;
        }

        // Additional validation can be added here based on specific business rules
        return true;
    }

    /**
     * Get user's subordinates based on organizational hierarchy
     */
    public function getUserSubordinates(User $user): array
    {
        if (! $this->validateHierarchy($user)) {
            return [];
        }

        // Get subordinates from the same organization with lower hierarchical levels
        return $user->getSubordinates()->toArray();
    }

    /**
     * Check if organization is part of national government
     */
    public function isNationalOrganization(User $user): bool
    {
        if (! $user->organization) {
            return false;
        }

        return in_array($user->organization->type, ['ministry', 'department', 'agency']);
    }

    /**
     * Check if organization is a county
     */
    public function isCountyOrganization(User $user): bool
    {
        if (! $user->organization) {
            return false;
        }

        return $user->organization->type === 'county';
    }
}
