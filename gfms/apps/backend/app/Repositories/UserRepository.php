<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * User repository implementing CRUD operations for users
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles user-related operations
 * - Open/Closed Principle: Can be extended for user-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on User model abstraction
 */
class UserRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new User;
    }

    /**
     * Find user by personal number
     */
    public function findByPersonalNumber(string $personalNumber)
    {
        return $this->findOneBy(['personal_number' => $personalNumber]);
    }

    /**
     * Find user by ID with roles and permissions loaded
     */
    public function findWithRolesAndPermissions(int $id)
    {
        return $this->findWith(['roles.permissions', 'organization'], $id);
    }

    /**
     * Get active users
     */
    public function getActiveUsers()
    {
        return $this->getModel()::where('is_active', true)->get();
    }

    /**
     * Find users by organization ID
     */
    public function findByOrganizationId(int $organizationId)
    {
        return $this->getModel()::where('organization_id', $organizationId)->get();
    }
}
