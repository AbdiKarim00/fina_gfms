<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'requester_id',
        'driver_id',
        'start_date',
        'end_date',
        'purpose',
        'destination',
        'passengers',
        'status',
        'priority',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'approved_at' => 'datetime',
        'passengers' => 'integer',
    ];

    /**
     * Get the vehicle for this booking.
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who requested this booking.
     */
    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    /**
     * Get the assigned driver for this booking.
     */
    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    /**
     * Get the user who approved this booking.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the history records for this booking.
     */
    public function history()
    {
        return $this->hasMany(BookingHistory::class)->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to only include pending bookings.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope a query to only include approved bookings.
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope a query to only include rejected bookings.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope a query to filter by priority.
     */
    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                     ->orWhereBetween('end_date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to find conflicting bookings for a vehicle.
     */
    public function scopeConflicts($query, int $vehicleId, $startDate, $endDate, ?int $excludeBookingId = null)
    {
        $query = $query->where('vehicle_id', $vehicleId)
                       ->whereIn('status', ['pending', 'approved'])
                       ->where(function ($q) use ($startDate, $endDate) {
                           $q->whereBetween('start_date', [$startDate, $endDate])
                             ->orWhereBetween('end_date', [$startDate, $endDate])
                             ->orWhere(function ($q2) use ($startDate, $endDate) {
                                 $q2->where('start_date', '<=', $startDate)
                                    ->where('end_date', '>=', $endDate);
                             });
                       });

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query;
    }

    /**
     * Check if booking is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if booking is approved.
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    /**
     * Check if booking is rejected.
     */
    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Check if booking can be modified.
     */
    public function canBeModified(): bool
    {
        return in_array($this->status, ['pending', 'approved']);
    }

    /**
     * Get duration in hours.
     */
    public function getDurationInHours(): float
    {
        return $this->start_date->diffInHours($this->end_date);
    }

    /**
     * Get duration in days.
     */
    public function getDurationInDays(): int
    {
        return $this->start_date->diffInDays($this->end_date);
    }
}
