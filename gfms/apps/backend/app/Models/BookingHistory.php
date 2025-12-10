<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'action',
        'old_values',
        'new_values',
        'notes',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking that owns the history.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user who made the change.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a history record for a booking action.
     */
    public static function logAction(
        Booking $booking,
        User $user,
        string $action,
        array $oldValues = null,
        array $newValues = null,
        string $notes = null,
        string $ipAddress = null,
        string $userAgent = null
    ): self {
        return static::create([
            'booking_id' => $booking->id,
            'user_id' => $user->id,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'notes' => $notes,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
        ]);
    }

    /**
     * Get formatted change description.
     */
    public function getChangeDescriptionAttribute(): string
    {
        $changes = [];
        
        if ($this->old_values && $this->new_values) {
            foreach ($this->new_values as $field => $newValue) {
                $oldValue = $this->old_values[$field] ?? null;
                if ($oldValue !== $newValue) {
                    $changes[] = "{$field}: '{$oldValue}' → '{$newValue}'";
                }
            }
        }
        
        return implode(', ', $changes);
    }

    /**
     * Get human-readable action description.
     */
    public function getActionDescriptionAttribute(): string
    {
        return match($this->action) {
            'created' => 'Booking created',
            'updated' => 'Booking updated',
            'approved' => 'Booking approved',
            'rejected' => 'Booking rejected',
            'cancelled' => 'Booking cancelled',
            'completed' => 'Booking completed',
            'driver_assigned' => 'Driver assigned',
            'driver_unassigned' => 'Driver unassigned',
            default => ucfirst($this->action),
        };
    }
}
