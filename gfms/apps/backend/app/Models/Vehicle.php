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
        'chassis_number',
        'fuel_type',
        'fuel_consumption_rate',
        'purchase_date',
        'purchase_price',
        'purchase_year',
        'status',
        'mileage',
        'capacity',
        'current_location',
        'original_location',
        'responsible_officer',
        'has_log_book',
        'notes',
        'organization_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year' => 'integer',
        'purchase_date' => 'date',
        'purchase_price' => 'decimal:2',
        'purchase_year' => 'integer',
        'fuel_consumption_rate' => 'decimal:2',
        'mileage' => 'integer',
        'capacity' => 'integer',
        'has_log_book' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * Get the organization that owns the vehicle.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

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
     * Scope a query to only include vehicles in maintenance.
     */
    public function scopeMaintenance($query)
    {
        return $query->where('status', 'maintenance');
    }

    /**
     * Scope a query to only include inactive vehicles.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
