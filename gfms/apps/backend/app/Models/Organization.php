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
        'level',
        'hierarchical_order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hierarchical_order' => 'integer',
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

    /**
     * Get the organizational level
     */
    public function getLevelAttribute(): string
    {
        return match ($this->type) {
            'ministry' => 'national',
            'department' => 'department',
            'agency' => 'agency',
            'county' => 'county',
            default => 'other'
        };
    }

    /**
     * Check if organization is part of national government
     */
    public function isNational(): bool
    {
        return in_array($this->type, ['ministry', 'department', 'agency']);
    }

    /**
     * Check if organization is a county
     */
    public function isCounty(): bool
    {
        return $this->type === 'county';
    }

    /**
     * Get all users in this organization and its sub-organizations
     */
    public function getAllUsers()
    {
        $orgIds = [$this->id];
        $children = $this->children;

        foreach ($children as $child) {
            $orgIds = array_merge($orgIds, $child->getAllChildrenIds());
        }

        return User::whereIn('organization_id', $orgIds)->get();
    }

    /**
     * Get all child organization IDs recursively
     */
    public function getAllChildrenIds(): array
    {
        $ids = [$this->id];
        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllChildrenIds());
        }

        return $ids;
    }

    /**
     * Get the hierarchy path of the organization
     */
    public function getHierarchyPath(): string
    {
        $path = [];
        $org = $this;

        while ($org) {
            array_unshift($path, $org->name);
            $org = $org->parent;
        }

        return implode(' > ', $path);
    }
}
