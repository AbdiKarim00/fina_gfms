<?php

namespace App\Repositories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleRepository
{
    /**
     * Get all vehicles with optional filters.
     */
    public function getAll(array $filters = []): Collection
    {
        $query = Vehicle::query();

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['fuel_type'])) {
            $query->where('fuel_type', $filters['fuel_type']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if (isset($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get paginated vehicles.
     */
    public function getPaginated(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = Vehicle::query();

        // Apply same filters as getAll
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['fuel_type'])) {
            $query->where('fuel_type', $filters['fuel_type']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('registration_number', 'like', "%{$search}%")
                  ->orWhere('make', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%");
            });
        }

        if (isset($filters['organization_id'])) {
            $query->where('organization_id', $filters['organization_id']);
        }

        return $query->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Find a vehicle by ID.
     */
    public function findById(int $id): ?Vehicle
    {
        return Vehicle::find($id);
    }

    /**
     * Find a vehicle by registration number.
     */
    public function findByRegistration(string $registrationNumber): ?Vehicle
    {
        return Vehicle::where('registration_number', $registrationNumber)->first();
    }

    /**
     * Create a new vehicle.
     */
    public function create(array $data): Vehicle
    {
        return Vehicle::create($data);
    }

    /**
     * Update a vehicle.
     */
    public function update(Vehicle $vehicle, array $data): bool
    {
        return $vehicle->update($data);
    }

    /**
     * Delete a vehicle.
     */
    public function delete(Vehicle $vehicle): bool
    {
        return $vehicle->delete();
    }

    /**
     * Get vehicle statistics.
     */
    public function getStatistics(): array
    {
        $total = Vehicle::count();
        $active = Vehicle::where('status', 'active')->count();
        $maintenance = Vehicle::where('status', 'maintenance')->count();
        $inactive = Vehicle::where('status', 'inactive')->count();

        // Fuel type distribution
        $fuelTypes = Vehicle::selectRaw('fuel_type, COUNT(*) as count')
            ->groupBy('fuel_type')
            ->pluck('count', 'fuel_type')
            ->toArray();

        // Average mileage
        $avgMileage = Vehicle::whereNotNull('mileage')
            ->where('mileage', '>', 0)
            ->avg('mileage');

        // Log book compliance
        $withLogBook = Vehicle::where('has_log_book', true)->count();

        return [
            'total' => $total,
            'active' => $active,
            'maintenance' => $maintenance,
            'inactive' => $inactive,
            'fuel_types' => $fuelTypes,
            'average_mileage' => round($avgMileage ?? 0, 2),
            'log_book_compliance' => [
                'with_log_book' => $withLogBook,
                'percentage' => $total > 0 ? round(($withLogBook / $total) * 100, 2) : 0,
            ],
        ];
    }
}
