<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Model;

/**
 * Vehicle repository implementing CRUD operations for vehicles
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles vehicle-related operations
 * - Open/Closed Principle: Can be extended for vehicle-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on Vehicle model abstraction
 */
class VehicleRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new Vehicle;
    }

    /**
     * Find vehicle by registration number
     */
    public function findByRegistrationNumber(string $registrationNumber)
    {
        return $this->findOneBy(['registration_number' => $registrationNumber]);
    }

    /**
     * Get active vehicles
     */
    public function getActiveVehicles()
    {
        return $this->getModel()::where('status', 'active')->get();
    }

    /**
     * Get vehicles by status
     */
    public function getByStatus(string $status)
    {
        return $this->getModel()::where('status', $status)->get();
    }
}
