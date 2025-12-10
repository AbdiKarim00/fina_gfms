<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DriverSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'type',
        'status',
        'start_date',
        'end_date',
        'reason',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    /**
     * Get the driver (user) that owns the schedule.
     */
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the user who created the schedule.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Scope a query to only include active schedules.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include schedules in a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('start_date', [$startDate, $endDate])
              ->orWhereBetween('end_date', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('start_date', '<=', $startDate)
                     ->where('end_date', '>=', $endDate);
              });
        });
    }

    /**
     * Check if driver is available during a period.
     */
    public static function isDriverAvailable($driverId, $startDate, $endDate, $excludeBookingId = null)
    {
        // Check driver schedules (leave, training, etc.)
        $hasScheduleConflict = static::where('driver_id', $driverId)
            ->active()
            ->inDateRange($startDate, $endDate)
            ->exists();

        if ($hasScheduleConflict) {
            return false;
        }

        // Check existing bookings
        $bookingQuery = \App\Models\Booking::where('driver_id', $driverId)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeBookingId) {
            $bookingQuery->where('id', '!=', $excludeBookingId);
        }

        $hasBookingConflict = $bookingQuery->exists();

        return !$hasBookingConflict;
    }

    /**
     * Get driver availability conflicts.
     */
    public static function getDriverConflicts($driverId, $startDate, $endDate, $excludeBookingId = null)
    {
        $conflicts = collect();

        // Get schedule conflicts
        $scheduleConflicts = static::where('driver_id', $driverId)
            ->active()
            ->inDateRange($startDate, $endDate)
            ->get()
            ->map(function ($schedule) {
                return [
                    'type' => 'schedule',
                    'reason' => $schedule->type . ': ' . $schedule->reason,
                    'start' => $schedule->start_date,
                    'end' => $schedule->end_date,
                ];
            });

        // Get booking conflicts
        $bookingQuery = \App\Models\Booking::where('driver_id', $driverId)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function ($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function ($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            });

        if ($excludeBookingId) {
            $bookingQuery->where('id', '!=', $excludeBookingId);
        }

        $bookingConflicts = $bookingQuery->get()
            ->map(function ($booking) {
                return [
                    'type' => 'booking',
                    'reason' => 'Existing booking: ' . $booking->purpose,
                    'start' => $booking->start_date,
                    'end' => $booking->end_date,
                ];
            });

        return $conflicts->concat($scheduleConflicts)->concat($bookingConflicts);
    }

    /**
     * Calculate driver working hours for a week.
     */
    public static function getDriverWeeklyHours($driverId, $weekStartDate)
    {
        $weekStart = Carbon::parse($weekStartDate)->startOfWeek();
        $weekEnd = $weekStart->copy()->endOfWeek();

        return \App\Models\Booking::where('driver_id', $driverId)
            ->whereIn('status', ['approved', 'completed'])
            ->whereBetween('start_date', [$weekStart, $weekEnd])
            ->get()
            ->sum(function ($booking) {
                return $booking->start_date->diffInHours($booking->end_date);
            });
    }
}
