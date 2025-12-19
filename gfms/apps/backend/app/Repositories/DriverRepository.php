<?php

namespace App\Repositories;

use App\Models\Driver;
use Illuminate\Database\Eloquent\Model;

/**
 * Driver repository implementing CRUD operations for drivers
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles driver-related operations
 * - Open/Closed Principle: Can be extended for driver-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on Driver model abstraction
 */
class DriverRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new Driver;
    }

    /**
     * Find driver by license number
     */
    public function findByLicenseNumber(string $licenseNumber)
    {
        return $this->findOneBy(['license_number' => $licenseNumber]);
    }

    /**
     * Find driver by user ID
     */
    public function findByUserId(int $userId)
    {
        return $this->findOneBy(['user_id' => $userId]);
    }

    /**
     * Get active drivers
     */
    public function getActiveDrivers()
    {
        return $this->getModel()::where('status', 'active')->get();
    }

    /**
     * Get drivers by status
     */
    public function getByStatus(string $status)
    {
        return $this->getModel()::where('status', $status)->get();
    }
}
