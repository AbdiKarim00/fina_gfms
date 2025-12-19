<?php

namespace App\Repositories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Model;

/**
 * Organization repository implementing CRUD operations for organizations
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles organization-related operations
 * - Open/Closed Principle: Can be extended for organization-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on Organization model abstraction
 */
class OrganizationRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new Organization;
    }

    /**
     * Find organization by code
     */
    public function findByCode(string $code)
    {
        return $this->findOneBy(['code' => $code]);
    }

    /**
     * Find organization by type
     */
    public function findByType(string $type)
    {
        return $this->getModel()::where('type', $type)->get();
    }

    /**
     * Get active organizations
     */
    public function getActiveOrganizations()
    {
        return $this->getModel()::where('is_active', true)->get();
    }

    /**
     * Get organizations by parent ID
     */
    public function getByParentId(?int $parentId)
    {
        return $this->getModel()::where('parent_id', $parentId)->get();
    }

    /**
     * Get root organizations (no parent)
     */
    public function getRootOrganizations()
    {
        return $this->getModel()::whereNull('parent_id')->get();
    }
}
