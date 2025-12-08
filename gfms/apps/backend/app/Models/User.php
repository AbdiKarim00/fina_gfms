<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles, LogsActivity;

    protected $fillable = [
        'personal_number',
        'name',
        'email',
        'phone',
        'password',
        'organization_id',
        'is_active',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    /**
     * Get the organization that the user belongs to
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Check if the account is currently locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Increment failed login attempts and lock if threshold reached
     */
    public function incrementFailedAttempts(): void
    {
        $this->increment('failed_login_attempts');
        
        if ($this->failed_login_attempts >= 5) {
            $this->update(['locked_until' => now()->addMinutes(30)]);
        }
    }

    /**
     * Reset failed login attempts
     */
    public function resetFailedAttempts(): void
    {
        $this->update([
            'failed_login_attempts' => 0,
            'locked_until' => null,
        ]);
    }

    /**
     * Configure activity logging
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['personal_number', 'name', 'email', 'phone', 'organization_id', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}
