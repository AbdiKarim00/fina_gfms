<?php

namespace App\Repositories;

use App\Models\FuelRecord;
use Illuminate\Database\Eloquent\Model;

/**
 * FuelRecord repository implementing CRUD operations for fuel records
 * Following SOLID principles:
 * - Single Responsibility Principle: Only handles fuel record operations
 * - Open/Closed Principle: Can be extended for fuel-specific operations
 * - Liskov Substitution Principle: Can substitute for BaseRepository
 * - Interface Segregation Principle: Implements only needed methods
 * - Dependency Inversion Principle: Depends on FuelRecord model abstraction
 */
class FuelRecordRepository extends BaseRepository
{
    /**
     * Get the model instance
     */
    protected function getModel(): Model
    {
        return new FuelRecord;
    }

    /**
     * Find fuel records by vehicle ID
     */
    public function findByVehicleId(int $vehicleId)
    {
        return $this->getModel()::where('vehicle_id', $vehicleId)->get();
    }

    /**
     * Find fuel records by driver ID
     */
    public function findByDriverId(int $driverId)
    {
        return $this->getModel()::where('driver_id', $driverId)->get();
    }

    /**
     * Find fuel records by date range
     */
    public function findByDateRange(string $startDate, string $endDate)
    {
        return $this->getModel()::whereBetween('date', [$startDate, $endDate])->get();
    }

    /**
     * Get fuel records above a certain cost threshold
     */
    public function getAboveCostThreshold(float $threshold)
    {
        return $this->getModel()::where('total_cost', '>', $threshold)->get();
    }
}
