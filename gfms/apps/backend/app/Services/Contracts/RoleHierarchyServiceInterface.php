<?php

namespace App\Services\Contracts;

use App\Models\User;

interface RoleHierarchyServiceInterface
{
    /**
     * Get role permissions based on hierarchy
     */
    public function getRolePermissions(string $roleName): array;

    /**
     * Get user permissions based on their roles
     */
    public function getUserPermissions(User $user): array;

    /**
     * Check if a user has a role with higher hierarchy
     */
    public function hasHigherRole(User $user, string $roleName): bool;

    /**
     * Get all roles with higher hierarchy than the given role
     */
    public function getHigherRoles(string $roleName): array;

    /**
     * Validate if a role exists in the hierarchy
     */
    public function isValidRole(string $roleName): bool;

    /**
     * Get the hierarchy level of a role
     */
    public function getRoleLevel(string $roleName): int;
}
