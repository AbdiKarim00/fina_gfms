<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Repositories\BookingRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingService
{
    public function __construct(
        private BookingRepository $bookingRepository
    ) {}

    /**
     * Get all bookings with filters.
     */
    public function getAllBookings(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookingRepository->getAll($filters, $perPage);
    }

    /**
     * Get pending bookings for approval.
     */
    public function getPendingBookings(int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookingRepository->getPending($perPage);
    }

    /**
     * Get bookings for a specific user.
     */
    public function getUserBookings(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->bookingRepository->getByUser($userId, $perPage);
    }

    /**
     * Get bookings for calendar view.
     */
    public function getCalendarBookings(string $startDate, string $endDate): Collection
    {
        return $this->bookingRepository->getCalendarBookings($startDate, $endDate);
    }

    /**
     * Get booking by ID.
     */
    public function getBookingById(int $id): ?Booking
    {
        return $this->bookingRepository->findById($id);
    }

    /**
     * Create a new booking.
     */
    public function createBooking(array $data, User $requester): Booking
    {
        // Check for conflicts
        $conflicts = $this->checkConflicts(
            $data['vehicle_id'],
            $data['start_date'],
            $data['end_date']
        );

        if ($conflicts->isNotEmpty()) {
            throw new \Exception('Vehicle is already booked for the selected time period');
        }

        // Set requester
        $data['requester_id'] = $requester->id;
        $data['status'] = 'pending';

        return $this->bookingRepository->create($data);
    }

    /**
     * Update a booking.
     */
    public function updateBooking(Booking $booking, array $data): bool
    {
        // Check if booking can be modified
        if (!$booking->canBeModified()) {
            throw new \Exception('This booking cannot be modified');
        }

        // If dates or vehicle changed, check for conflicts
        if (
            isset($data['vehicle_id']) || 
            isset($data['start_date']) || 
            isset($data['end_date'])
        ) {
            $vehicleId = $data['vehicle_id'] ?? $booking->vehicle_id;
            $startDate = $data['start_date'] ?? $booking->start_date;
            $endDate = $data['end_date'] ?? $booking->end_date;

            $conflicts = $this->checkConflicts(
                $vehicleId,
                $startDate,
                $endDate,
                $booking->id
            );

            if ($conflicts->isNotEmpty()) {
                throw new \Exception('Vehicle is already booked for the selected time period');
            }
        }

        return $this->bookingRepository->update($booking, $data);
    }

    /**
     * Approve a booking.
     */
    public function approveBooking(Booking $booking, User $approver): bool
    {
        if (!$booking->isPending()) {
            throw new \Exception('Only pending bookings can be approved');
        }

        // Check for conflicts one more time
        $conflicts = $this->checkConflicts(
            $booking->vehicle_id,
            $booking->start_date,
            $booking->end_date,
            $booking->id
        );

        if ($conflicts->isNotEmpty()) {
            throw new \Exception('Vehicle is already booked for the selected time period');
        }

        $oldStatus = $booking->status;
        
        $updateData = [
            'status' => 'approved',
            'approved_by' => $approver->id,
            'approved_at' => now(),
        ];

        $result = $this->bookingRepository->update($booking, $updateData);

        if ($result) {
            // Log the action
            \App\Models\BookingHistory::logAction(
                $booking,
                $approver,
                'approved',
                ['status' => $oldStatus],
                $updateData,
                'Booking approved by ' . $approver->name,
                request()->ip(),
                request()->userAgent()
            );

            // Refresh booking to get updated data
            $booking->refresh();
            
            // Send notification to requester
            $booking->requester->notify(
                new \App\Notifications\BookingStatusChanged($booking, $oldStatus, 'approved', $approver)
            );

            // Send notification to driver if assigned
            if ($booking->driver) {
                $booking->driver->notify(
                    new \App\Notifications\DriverAssigned($booking)
                );
            }
        }

        return $result;
    }

    /**
     * Reject a booking.
     */
    public function rejectBooking(Booking $booking, User $approver, string $reason): bool
    {
        if (!$booking->isPending()) {
            throw new \Exception('Only pending bookings can be rejected');
        }

        $oldStatus = $booking->status;
        
        $updateData = [
            'status' => 'rejected',
            'approved_by' => $approver->id,
            'approved_at' => now(),
            'rejection_reason' => $reason,
        ];

        $result = $this->bookingRepository->update($booking, $updateData);

        if ($result) {
            // Log the action
            \App\Models\BookingHistory::logAction(
                $booking,
                $approver,
                'rejected',
                ['status' => $oldStatus],
                $updateData,
                'Booking rejected by ' . $approver->name . '. Reason: ' . $reason,
                request()->ip(),
                request()->userAgent()
            );

            // Refresh booking to get updated data
            $booking->refresh();
            
            // Send notification to requester
            $booking->requester->notify(
                new \App\Notifications\BookingStatusChanged($booking, $oldStatus, 'rejected', $approver)
            );
        }

        return $result;
    }

    /**
     * Cancel a booking.
     */
    public function cancelBooking(Booking $booking): bool
    {
        if (!$booking->canBeModified()) {
            throw new \Exception('This booking cannot be cancelled');
        }

        return $this->bookingRepository->update($booking, [
            'status' => 'cancelled',
        ]);
    }

    /**
     * Complete a booking.
     */
    public function completeBooking(Booking $booking): bool
    {
        if (!$booking->isApproved()) {
            throw new \Exception('Only approved bookings can be completed');
        }

        return $this->bookingRepository->update($booking, [
            'status' => 'completed',
        ]);
    }

    /**
     * Check for booking conflicts.
     */
    public function checkConflicts(
        int $vehicleId,
        string $startDate,
        string $endDate,
        ?int $excludeBookingId = null
    ): Collection {
        return $this->bookingRepository->checkConflicts(
            $vehicleId,
            $startDate,
            $endDate,
            $excludeBookingId
        );
    }

    /**
     * Get available vehicles for a date range.
     */
    public function getAvailableVehicles(string $startDate, string $endDate): Collection
    {
        return $this->bookingRepository->getAvailableVehicles($startDate, $endDate);
    }

    /**
     * Get booking statistics.
     */
    public function getStatistics(): array
    {
        return $this->bookingRepository->getStatistics();
    }

    /**
     * Bulk approve bookings.
     */
    public function bulkApprove(array $bookingIds, User $approver): array
    {
        $results = [
            'approved' => [],
            'failed' => [],
        ];

        foreach ($bookingIds as $bookingId) {
            try {
                $booking = $this->getBookingById($bookingId);
                
                if (!$booking) {
                    $results['failed'][] = [
                        'id' => $bookingId,
                        'reason' => 'Booking not found',
                    ];
                    continue;
                }

                $this->approveBooking($booking, $approver);
                $results['approved'][] = $bookingId;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'id' => $bookingId,
                    'reason' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }
}
