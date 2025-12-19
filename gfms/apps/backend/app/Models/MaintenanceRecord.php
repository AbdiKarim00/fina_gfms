<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MaintenanceRecord extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'vehicle_id',
        'maintenance_date',
        'maintenance_type',
        'odometer_reading',
        'description',
        'cost',
        'service_provider',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'maintenance_date' => 'date',
        'odometer_reading' => 'integer',
        'cost' => 'decimal:2',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'maintenance_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the vehicle that owns the maintenance record.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
