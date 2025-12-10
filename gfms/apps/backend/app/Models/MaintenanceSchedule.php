<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_id',
        'type',
        'status',
        'scheduled_start',
        'scheduled_end',
        'actual_start',
        'actual_end',
        'description',
        'notes',
        'estimated_cost',
        'actual_cost',
        'scheduled_by',
        'performed_by',
        'service_provider',
    ];

    protected $casts = [
        'scheduled_start' => 'datetime',
        'scheduled_end' => 'datetime',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
    ];

    /**
     * Get the vehicle that owns the maintenance schedule.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the user who scheduled the maintenance.
     */
    public function scheduledBy()
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }

    /**
     * Get the user who performed the maintenance.
     */
    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Scope a query to only include active maintenance.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['scheduled', 'in_progress']);
    }

    /**
     * Scope a query to only include maintenance in a date range.
     */
    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function ($q) use ($startDate, $endDate) {
            $q->whereBetween('scheduled_start', [$startDate, $endDate])
              ->orWhereBetween('scheduled_end', [$startDate, $endDate])
              ->orWhere(function ($q2) use ($startDate, $endDate) {
                  $q2->where('scheduled_start', '<=', $startDate)
                     ->where('scheduled_end', '>=', $endDate);
              });
        });
    }

    /**
     * Check if maintenance conflicts with a booking period.
     */
    public static function hasConflict($vehicleId, $startDate, $endDate, $excludeId = null)
    {
        $query = static::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->inDateRange($startDate, $endDate);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    /**
     * Get maintenance conflicts for a booking period.
     */
    public static function getConflicts($vehicleId, $startDate, $endDate, $excludeId = null)
    {
        $query = static::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->inDateRange($startDate, $endDate);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->get();
    }
}
