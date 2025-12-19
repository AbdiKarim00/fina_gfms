<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, LogsActivity, Notifiable, SoftDeletes;

    protected $fillable = [
        'personal_number',
        'name',
        'email',
        'phone',
        'password',
        'organization_id',
        'job_group',
        'position',
        'hierarchical_level',
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
        'hierarchical_level' => 'integer',
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
            ->logOnly(['personal_number', 'name', 'email', 'phone', 'organization_id', 'is_active', 'job_group', 'position', 'hierarchical_level'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * Check if user has a specific role in the hierarchy
     */
    public function hasRoleInHierarchy(string $role): bool
    {
        $hierarchyRoles = [
            'cabinet_secretary',
            'principal_secretary',
            'accounting_officer',
            'gfmd_director',
            'fleet_manager',
            'cmte_official',
            'gvcu_officer',
            'authorized_driver',
            'm_and_e_specialist',
            'audit_officer',
            'policy_analyst',
        ];

        return in_array($role, $hierarchyRoles);
    }

    /**
     * Get users who report to this user based on hierarchy
     */
    public function getSubordinates()
    {
        // This would depend on the organizational structure
        // For now, we'll return users from the same organization with lower hierarchical levels
        return User::where('organization_id', $this->organization_id)
            ->where('hierarchical_level', '<', $this->hierarchical_level)
            ->get();
    }

    /**
     * Check if user can approve requests based on their role
     */
    public function canApproveRequests(): bool
    {
        $approvingRoles = [
            'cabinet_secretary',
            'principal_secretary',
            'accounting_officer',
            'gfmd_director',
            'fleet_manager',
            'cmte_official',
        ];

        return $this->roles->pluck('name')->intersect($approvingRoles)->isNotEmpty();
    }
}
