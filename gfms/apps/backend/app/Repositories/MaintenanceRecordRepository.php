<?php

namespace App\Repositories;

use App\Models\MaintenanceRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * MaintenanceRecord repository implementing CRUD operations for maintenance records
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles maintenance record operations
 * - Open/Closed Principle: Can be extended for maintenance-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on MaintenanceRecord model abstraction
 */
class MaintenanceRecordRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new MaintenanceRecord;
    }

    /**
     * Find maintenance records by vehicle ID
     */
    public function findByVehicleId(int $vehicleId)
    {
        return $this->getModel()::where('vehicle_id', $vehicleId)->get();
    }

    /**
     * Find maintenance records by date range
     */
    public function findByDateRange(string $startDate, string $endDate)
    {
        return $this->getModel()::whereBetween('maintenance_date', [$startDate, $endDate])->get();
    }

    /**
     * Get maintenance records by type
     */
    public function getByType(string $type)
    {
        return $this->getModel()::where('maintenance_type', $type)->get();
    }

    /**
     * Get upcoming maintenance records
     */
    public function getUpcomingMaintenance()
    {
        return $this->getModel()::where('maintenance_date', '>', now())->orderBy('maintenance_date')->get();
    }
}
