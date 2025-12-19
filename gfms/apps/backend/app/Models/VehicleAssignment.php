<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VehicleAssignment extends Model
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
        'assigned_date',
        'returned_date',
        'purpose',
        'assigned_by',
        'received_by',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'assigned_date' => 'date',
        'returned_date' => 'date',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'assigned_date',
        'returned_date',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the vehicle that owns the assignment.
     */
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    /**
     * Get the driver that owns the assignment.
     */
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
