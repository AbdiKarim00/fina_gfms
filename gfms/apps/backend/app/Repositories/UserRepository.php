<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * Find user by personal number
     */
    public function findByPersonalNumber(string $personalNumber): ?User
    {
        return User::where('personal_number', $personalNumber)->first();
    }

    /**
     * Find user by ID with organization scoping
     */
    public function find(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find user by ID with roles and permissions loaded
     */
    public function findWithRolesAndPermissions(int $id): ?User
    {
        return User::with(['roles.permissions', 'organization'])->find($id);
    }
}
