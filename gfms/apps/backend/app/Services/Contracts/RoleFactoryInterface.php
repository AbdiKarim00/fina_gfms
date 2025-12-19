<?php

namespace App\Services\Contracts;

use Spatie\Permission\Models\Role;

interface RoleFactoryInterface
{
    /**
     * Create a role with the specified name and permissions
     */
    public function createRole(string $roleName, array $permissions = []): Role;

    /**
     * Create a hierarchical role with predefined permissions
     */
    public function createHierarchicalRole(string $roleName): Role;
}
