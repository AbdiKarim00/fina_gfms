<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Repositories\VehicleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class VehicleService
{
    protected VehicleRepository $repository;

    public function __construct(VehicleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get all vehicles with optional filters.
     */
    public function getAllVehicles(array $filters = []): Collection
    {
        return $this->repository->getAll($filters);
    }

    /**
     * Get paginated vehicles.
     */
    public function getPaginatedVehicles(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->getPaginated($perPage, $filters);
    }

    /**
     * Get a single vehicle by ID.
     */
    public function getVehicleById(int $id): ?Vehicle
    {
        return $this->repository->findById($id);
    }

    /**
     * Create a new vehicle.
     */
    public function createVehicle(array $data): Vehicle
    {
        // Normalize registration number
        if (isset($data['registration_number'])) {
            $data['registration_number'] = strtoupper(trim($data['registration_number']));
        }

        // Set defaults
        $data['has_log_book'] = $data['has_log_book'] ?? true;
        $data['status'] = $data['status'] ?? 'active';

        return $this->repository->create($data);
    }

    /**
     * Update a vehicle.
     */
    public function updateVehicle(int $id, array $data): ?Vehicle
    {
        $vehicle = $this->repository->findById($id);

        if (!$vehicle) {
            return null;
        }

        // Normalize registration number if provided
        if (isset($data['registration_number'])) {
            $data['registration_number'] = strtoupper(trim($data['registration_number']));
        }

        $this->repository->update($vehicle, $data);

        return $vehicle->fresh();
    }

    /**
     * Delete a vehicle.
     */
    public function deleteVehicle(int $id): bool
    {
        $vehicle = $this->repository->findById($id);

        if (!$vehicle) {
            return false;
        }

        return $this->repository->delete($vehicle);
    }

    /**
     * Check if registration number is unique.
     */
    public function isRegistrationUnique(string $registrationNumber, ?int $excludeId = null): bool
    {
        $vehicle = $this->repository->findByRegistration(strtoupper(trim($registrationNumber)));

        if (!$vehicle) {
            return true;
        }

        return $excludeId && $vehicle->id === $excludeId;
    }

    /**
     * Get vehicle statistics.
     */
    public function getStatistics(): array
    {
        return $this->repository->getStatistics();
    }

    /**
     * Bulk update vehicles.
     */
    public function bulkUpdate(array $ids, array $data): int
    {
        return Vehicle::whereIn('id', $ids)->update($data);
    }

    /**
     * Bulk delete vehicles.
     */
    public function bulkDelete(array $ids): int
    {
        return Vehicle::whereIn('id', $ids)->delete();
    }
}
