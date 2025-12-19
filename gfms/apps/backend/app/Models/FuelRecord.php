<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuelRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'driver_id',
        'date',
        'liters',
        'cost_per_liter',
        'total_cost',
        'odometer_reading',
        'fuel_station',
        'receipt_number',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'liters' => 'decimal:2',
        'cost_per_liter' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'odometer_reading' => 'integer',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the vehicle that owns the fuel record.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver that owns the fuel record.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
