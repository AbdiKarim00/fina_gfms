<?php

namespace App\Repositories;

use App\Models\Booking;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingRepository
{
    /**
     * Get all bookings with pagination.
     */
    public function getAll(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Booking::with(['vehicle', 'requester', 'driver', 'approver']);

        // Apply filters
        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['priority'])) {
            $query->where('priority', $filters['priority']);
        }

        if (isset($filters['vehicle_id'])) {
            $query->where('vehicle_id', $filters['vehicle_id']);
        }

        if (isset($filters['requester_id'])) {
            $query->where('requester_id', $filters['requester_id']);
        }

        if (isset($filters['start_date']) && isset($filters['end_date'])) {
            $query->dateRange($filters['start_date'], $filters['end_date']);
        }

        // Sort by priority and created date (PostgreSQL compatible)
        $query->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END")
              ->orderBy('created_at', 'desc');

        return $query->paginate($perPage);
    }

    /**
     * Get pending bookings.
     */
    public function getPending(int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with(['vehicle', 'requester', 'driver'])
            ->pending()
            ->orderByRaw("CASE priority WHEN 'high' THEN 1 WHEN 'medium' THEN 2 WHEN 'low' THEN 3 END")
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    /**
     * Get bookings for a specific user.
     */
    public function getByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with(['vehicle', 'driver', 'approver'])
            ->where('requester_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get bookings for a specific vehicle.
     */
    public function getByVehicle(int $vehicleId, int $perPage = 15): LengthAwarePaginator
    {
        return Booking::with(['requester', 'driver', 'approver'])
            ->where('vehicle_id', $vehicleId)
            ->orderBy('start_date', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get bookings for calendar view.
     */
    public function getCalendarBookings(string $startDate, string $endDate): Collection
    {
        return Booking::with(['vehicle', 'requester', 'driver'])
            ->whereIn('status', ['pending', 'approved', 'completed'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->orderBy('start_date', 'asc')
            ->get();
    }

    /**
     * Find booking by ID.
     */
    public function findById(int $id): ?Booking
    {
        return Booking::with(['vehicle', 'requester', 'driver', 'approver'])->find($id);
    }

    /**
     * Create a new booking.
     */
    public function create(array $data): Booking
    {
        return Booking::create($data);
    }

    /**
     * Update a booking.
     */
    public function update(Booking $booking, array $data): bool
    {
        return $booking->update($data);
    }

    /**
     * Delete a booking.
     */
    public function delete(Booking $booking): bool
    {
        return $booking->delete();
    }

    /**
     * Check for conflicting bookings.
     */
    public function checkConflicts(
        int $vehicleId,
        string $startDate,
        string $endDate,
        ?int $excludeBookingId = null
    ): Collection {
        return Booking::conflicts($vehicleId, $startDate, $endDate, $excludeBookingId)
            ->with(['requester'])
            ->get();
    }

    /**
     * Get booking statistics.
     */
    public function getStatistics(): array
    {
        return [
            'total' => Booking::count(),
            'pending' => Booking::pending()->count(),
            'approved' => Booking::approved()->count(),
            'rejected' => Booking::rejected()->count(),
            'completed' => Booking::where('status', 'completed')->count(),
            'cancelled' => Booking::where('status', 'cancelled')->count(),
            'by_priority' => [
                'high' => Booking::byPriority('high')->count(),
                'medium' => Booking::byPriority('medium')->count(),
                'low' => Booking::byPriority('low')->count(),
            ],
        ];
    }

    /**
     * Get available vehicles for a date range.
     */
    public function getAvailableVehicles(string $startDate, string $endDate): Collection
    {
        $bookedVehicleIds = Booking::whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->whereBetween('start_date', [$startDate, $endDate])
                      ->orWhereBetween('end_date', [$startDate, $endDate])
                      ->orWhere(function ($q) use ($startDate, $endDate) {
                          $q->where('start_date', '<=', $startDate)
                            ->where('end_date', '>=', $endDate);
                      });
            })
            ->pluck('vehicle_id')
            ->toArray();

        return \App\Models\Vehicle::whereNotIn('id', $bookedVehicleIds)
            ->where('status', 'active')
            ->get();
    }
}
