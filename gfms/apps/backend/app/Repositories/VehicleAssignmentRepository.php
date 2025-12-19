<?php

namespace App\Repositories;

use App\Models\VehicleAssignment;
use Illuminate\Database\Eloquent\Model;

/**
 * VehicleAssignment repository implementing CRUD operations for vehicle assignments
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles vehicle assignment operations
 * - Open/Closed Principle: Can be extended for assignment-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on VehicleAssignment model abstraction
 */
class VehicleAssignmentRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new VehicleAssignment;
    }

    /**
     * Find assignments by vehicle ID
     */
    public function findByVehicleId(int $vehicleId)
    {
        return $this->getModel()::where('vehicle_id', $vehicleId)->get();
    }

    /**
     * Find assignments by driver ID
     */
    public function findByDriverId(int $driverId)
    {
        return $this->getModel()::where('driver_id', $driverId)->get();
    }

    /**
     * Find active assignments (not returned)
     */
    public function findActiveAssignments()
    {
        return $this->getModel()::whereNull('returned_date')->get();
    }

    /**
     * Find assignments by date range
     */
    public function findByDateRange(string $startDate, string $endDate)
    {
        return $this->getModel()::whereBetween('assigned_date', [$startDate, $endDate])->get();
    }
}
