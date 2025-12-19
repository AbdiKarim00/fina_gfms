<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Driver extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'license_number',
        'license_expiry_date',
        'license_class',
        'date_hired',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'license_expiry_date' => 'date',
        'date_hired' => 'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'license_expiry_date',
        'date_hired',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the user that owns the driver.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the vehicle assignments for the driver.
     */
    public function assignments()
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    /**
     * Get the fuel records for the driver.
     */
    public function fuelRecords()
    {
        return $this->hasMany(FuelRecord::class);
    }

    /**
     * Scope a query to only include active drivers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include drivers by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
