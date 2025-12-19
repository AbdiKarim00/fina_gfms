<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'registration_number',
        'make',
        'model',
        'year',
        'color',
        'vin',
        'engine_number',
        'fuel_type',
        'fuel_consumption_rate',
        'purchase_date',
        'purchase_price',
        'status',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'fuel_consumption_rate' => 'decimal:2',
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'purchase_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the maintenance records for the vehicle.
     */
    public function maintenanceRecords()
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    /**
     * Get the fuel records for the vehicle.
     */
    public function fuelRecords()
    {
        return $this->hasMany(FuelRecord::class);
    }

    /**
     * Get the assignments for the vehicle.
     */
    public function assignments()
    {
        return $this->hasMany(VehicleAssignment::class);
    }

    /**
     * Scope a query to only include active vehicles.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include vehicles by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
