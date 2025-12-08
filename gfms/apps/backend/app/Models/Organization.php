<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'type',
        'parent_id',
        'email',
        'phone',
        'address',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the parent organization
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'parent_id');
    }

    /**
     * Get child organizations
     */
    public function children(): HasMany
    {
        return $this->hasMany(Organization::class, 'parent_id');
    }

    /**
     * Get users belonging to this organization
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Scope to get only active organizations
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by organization type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get ministries
     */
    public function scopeMinistries($query)
    {
        return $query->where('type', 'ministry');
    }

    /**
     * Scope to get counties
     */
    public function scopeCounties($query)
    {
        return $query->where('type', 'county');
    }
}
