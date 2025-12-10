<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\Booking;
use App\Models\MaintenanceSchedule;
use App\Models\DriverSchedule;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class ConflictResolutionService
{
    /**
     * Get alternative vehicles when the requested vehicle is unavailable.
     */
    public function getSimilarAvailableVehicles(
        Vehicle $originalVehicle,
        string $startDate,
        string $endDate,
        int $passengers = null,
        int $limit = 3
    ): Collection {
        // Enforce maximum limit of 3 for better UX
        $limit = min($limit, 3);
        $startDateTime = Carbon::parse($startDate);
        $endDateTime = Carbon::parse($endDate);
        
        return Vehicle::where('status', 'active')
            ->where('id', '!=', $originalVehicle->id)
            // Match or exceed capacity requirements
            ->when($passengers, function($query, $passengers) use ($originalVehicle) {
                return $query->where('capacity', '>=', max($passengers, (int)($originalVehicle->capacity * 0.8)));
            }, function($query) use ($originalVehicle) {
                return $query->where('capacity', '>=', (int)($originalVehicle->capacity * 0.8));
            })
            // Exclude vehicles with maintenance conflicts
            ->whereDoesntHave('maintenanceSchedules', function($q) use ($startDateTime, $endDateTime) {
                $q->whereIn('status', ['scheduled', 'in_progress'])
                  ->where(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->whereBetween('scheduled_start', [$startDateTime, $endDateTime])
                         ->orWhereBetween('scheduled_end', [$startDateTime, $endDateTime])
                         ->orWhere(function($q3) use ($startDateTime, $endDateTime) {
                             $q3->where('scheduled_start', '<=', $startDateTime)
                                ->where('scheduled_end', '>=', $endDateTime);
                         });
                  });
            })
            // Exclude vehicles with booking conflicts
            ->whereDoesntHave('bookings', function($q) use ($startDateTime, $endDateTime) {
                $q->whereIn('status', ['approved', 'pending'])
                  ->where(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->whereBetween('start_date', [$startDateTime, $endDateTime])
                         ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
                         ->orWhere(function($q3) use ($startDateTime, $endDateTime) {
                             $q3->where('start_date', '<=', $startDateTime)
                                ->where('end_date', '>=', $endDateTime);
                         });
                  });
            })
            // Order by similarity (same make/model first, then same make, then by capacity)
            ->orderByRaw("
                CASE 
                    WHEN make = ? AND model = ? THEN 1
                    WHEN make = ? THEN 2
                    ELSE 3
                END, capacity DESC
            ", [$originalVehicle->make, $originalVehicle->model, $originalVehicle->make])
            ->limit($limit)
            ->get()
            ->map(function($vehicle) use ($startDateTime, $endDateTime) {
                return [
                    'id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                    'make' => $vehicle->make,
                    'model' => $vehicle->model,
                    'capacity' => $vehicle->capacity,
                    'fuel_type' => $vehicle->fuel_type,
                    'current_location' => $vehicle->current_location,
                    'availability_status' => $this->getVehicleAvailabilityStatus($vehicle, $startDateTime, $endDateTime),
                ];
            });
    }

    /**
     * Get alternative time slots for a vehicle.
     */
    public function getAlternativeTimeSlots(
        int $vehicleId,
        string $date,
        int $durationHours,
        int $limit = 3
    ): Collection {
        // Enforce maximum limit of 3 for better UX
        $limit = min($limit, 3);
        $targetDate = Carbon::parse($date)->startOfDay();
        $conflicts = $this->getVehicleConflicts($vehicleId, $targetDate);
        
        // Define business hours
        $dayStart = $targetDate->copy()->setHour(6); // 6 AM
        $dayEnd = $targetDate->copy()->setHour(22);   // 10 PM
        
        $availableSlots = collect();
        
        // Find gaps between conflicts
        $timeGaps = $this->findTimeGaps($conflicts, $dayStart, $dayEnd, $durationHours);
        
        foreach ($timeGaps as $gap) {
            $availableSlots->push([
                'start_time' => $gap['start']->format('H:i'),
                'end_time' => $gap['end']->format('H:i'),
                'start_datetime' => $gap['start']->toISOString(),
                'end_datetime' => $gap['end']->toISOString(),
                'duration_hours' => $gap['duration'],
                'period' => $this->getTimePeriodLabel($gap['start']),
            ]);
        }
        
        // Also check next few days
        for ($i = 1; $i <= 3; $i++) {
            $nextDate = $targetDate->copy()->addDays($i);
            $nextDayConflicts = $this->getVehicleConflicts($vehicleId, $nextDate);
            
            if ($nextDayConflicts->isEmpty()) {
                $availableSlots->push([
                    'start_time' => '06:00',
                    'end_time' => '22:00',
                    'start_datetime' => $nextDate->copy()->setHour(6)->toISOString(),
                    'end_datetime' => $nextDate->copy()->setHour(22)->toISOString(),
                    'duration_hours' => 16,
                    'period' => 'All day',
                    'date' => $nextDate->format('M j, Y'),
                    'is_future_date' => true,
                ]);
            }
        }
        
        return $availableSlots->take($limit);
    }

    /**
     * Get alternative drivers when the requested driver is unavailable.
     */
    public function getAlternativeDrivers(
        int $originalDriverId,
        string $startDate,
        string $endDate,
        int $limit = 3
    ): Collection {
        // Enforce maximum limit of 3 for better UX
        $limit = min($limit, 3);
        $startDateTime = Carbon::parse($startDate);
        $endDateTime = Carbon::parse($endDate);
        
        // Get users with driver role
        return \App\Models\User::whereHas('roles', function($q) {
                $q->where('name', 'Driver');
            })
            ->where('id', '!=', $originalDriverId)
            ->where('is_active', true)
            // Check driver availability
            ->whereDoesntHave('driverSchedules', function($q) use ($startDateTime, $endDateTime) {
                $q->where('status', 'active')
                  ->where(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->whereBetween('start_date', [$startDateTime, $endDateTime])
                         ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
                         ->orWhere(function($q3) use ($startDateTime, $endDateTime) {
                             $q3->where('start_date', '<=', $startDateTime)
                                ->where('end_date', '>=', $endDateTime);
                         });
                  });
            })
            // Check existing bookings
            ->whereDoesntHave('driverBookings', function($q) use ($startDateTime, $endDateTime) {
                $q->whereIn('status', ['approved', 'pending'])
                  ->where(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->whereBetween('start_date', [$startDateTime, $endDateTime])
                         ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
                         ->orWhere(function($q3) use ($startDateTime, $endDateTime) {
                             $q3->where('start_date', '<=', $startDateTime)
                                ->where('end_date', '>=', $endDateTime);
                         });
                  });
            })
            ->limit($limit)
            ->get()
            ->map(function($driver) use ($startDateTime, $endDateTime) {
                $weeklyHours = DriverSchedule::getDriverWeeklyHours($driver->id, $startDateTime);
                $bookingHours = $startDateTime->diffInHours($endDateTime);
                
                return [
                    'id' => $driver->id,
                    'name' => $driver->name,
                    'phone' => $driver->phone,
                    'weekly_hours' => $weeklyHours,
                    'remaining_hours' => max(0, 60 - $weeklyHours),
                    'can_take_booking' => ($weeklyHours + $bookingHours) <= 60,
                ];
            })
            ->filter(function($driver) {
                return $driver['can_take_booking'];
            });
    }

    /**
     * Get comprehensive conflict resolution suggestions.
     */
    public function getConflictResolutionSuggestions(
        int $vehicleId,
        string $startDate,
        string $endDate,
        int $passengers = null,
        int $driverId = null
    ): array {
        $vehicle = Vehicle::find($vehicleId);
        $startDateTime = Carbon::parse($startDate);
        $endDateTime = Carbon::parse($endDate);
        $durationHours = $startDateTime->diffInHours($endDateTime);
        
        return [
            'original_request' => [
                'vehicle' => $vehicle ? [
                    'id' => $vehicle->id,
                    'registration_number' => $vehicle->registration_number,
                    'make' => $vehicle->make,
                    'model' => $vehicle->model,
                    'status' => $vehicle->status,
                ] : null,
                'start_date' => $startDateTime->toISOString(),
                'end_date' => $endDateTime->toISOString(),
                'duration_hours' => $durationHours,
                'passengers' => $passengers,
            ],
            'alternative_vehicles' => $vehicle ? $this->getSimilarAvailableVehicles(
                $vehicle,
                $startDate,
                $endDate,
                $passengers
            ) : collect(),
            'alternative_times' => $this->getAlternativeTimeSlots(
                $vehicleId,
                $startDate,
                $durationHours
            ),
            'alternative_drivers' => $driverId ? $this->getAlternativeDrivers(
                $driverId,
                $startDate,
                $endDate
            ) : collect(),
            'conflict_details' => $this->getConflictDetails($vehicleId, $startDate, $endDate, $driverId),
        ];
    }

    /**
     * Get detailed conflict information.
     */
    private function getConflictDetails(int $vehicleId, string $startDate, string $endDate, int $driverId = null): array
    {
        $conflicts = [];
        $startDateTime = Carbon::parse($startDate);
        $endDateTime = Carbon::parse($endDate);
        
        // Vehicle status conflicts
        $vehicle = Vehicle::find($vehicleId);
        if ($vehicle && $vehicle->status !== 'active') {
            $conflicts[] = [
                'type' => 'vehicle_status',
                'message' => "Vehicle is {$vehicle->status}",
                'details' => $vehicle->notes,
            ];
        }
        
        // Maintenance conflicts
        $maintenanceConflicts = MaintenanceSchedule::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where(function($q) use ($startDateTime, $endDateTime) {
                $q->whereBetween('scheduled_start', [$startDateTime, $endDateTime])
                  ->orWhereBetween('scheduled_end', [$startDateTime, $endDateTime])
                  ->orWhere(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->where('scheduled_start', '<=', $startDateTime)
                         ->where('scheduled_end', '>=', $endDateTime);
                  });
            })
            ->get();
            
        foreach ($maintenanceConflicts as $maintenance) {
            $conflicts[] = [
                'type' => 'maintenance',
                'message' => "Scheduled {$maintenance->type}",
                'start' => $maintenance->scheduled_start->format('M j, Y H:i'),
                'end' => $maintenance->scheduled_end->format('M j, Y H:i'),
                'details' => $maintenance->description,
            ];
        }
        
        // Booking conflicts
        $bookingConflicts = Booking::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function($q) use ($startDateTime, $endDateTime) {
                $q->whereBetween('start_date', [$startDateTime, $endDateTime])
                  ->orWhereBetween('end_date', [$startDateTime, $endDateTime])
                  ->orWhere(function($q2) use ($startDateTime, $endDateTime) {
                      $q2->where('start_date', '<=', $startDateTime)
                         ->where('end_date', '>=', $endDateTime);
                  });
            })
            ->with('requester')
            ->get();
            
        foreach ($bookingConflicts as $booking) {
            $conflicts[] = [
                'type' => 'booking',
                'message' => "Existing booking by {$booking->requester->name}",
                'start' => $booking->start_date->format('M j, Y H:i'),
                'end' => $booking->end_date->format('M j, Y H:i'),
                'details' => $booking->purpose,
            ];
        }
        
        // Driver conflicts
        if ($driverId) {
            $driverConflicts = DriverSchedule::getDriverConflicts($driverId, $startDate, $endDate);
            foreach ($driverConflicts as $conflict) {
                $conflicts[] = [
                    'type' => 'driver',
                    'message' => $conflict['reason'],
                    'start' => Carbon::parse($conflict['start'])->format('M j, Y H:i'),
                    'end' => Carbon::parse($conflict['end'])->format('M j, Y H:i'),
                ];
            }
        }
        
        return $conflicts;
    }

    /**
     * Get vehicle conflicts for a specific date.
     */
    private function getVehicleConflicts(int $vehicleId, Carbon $date): Collection
    {
        $dayStart = $date->copy()->startOfDay();
        $dayEnd = $date->copy()->endOfDay();
        
        $conflicts = collect();
        
        // Maintenance conflicts
        $maintenanceConflicts = MaintenanceSchedule::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where(function($q) use ($dayStart, $dayEnd) {
                $q->whereBetween('scheduled_start', [$dayStart, $dayEnd])
                  ->orWhereBetween('scheduled_end', [$dayStart, $dayEnd])
                  ->orWhere(function($q2) use ($dayStart, $dayEnd) {
                      $q2->where('scheduled_start', '<=', $dayStart)
                         ->where('scheduled_end', '>=', $dayEnd);
                  });
            })
            ->get();
            
        foreach ($maintenanceConflicts as $maintenance) {
            $conflicts->push([
                'start' => $maintenance->scheduled_start,
                'end' => $maintenance->scheduled_end,
                'type' => 'maintenance',
            ]);
        }
        
        // Booking conflicts
        $bookingConflicts = Booking::where('vehicle_id', $vehicleId)
            ->whereIn('status', ['approved', 'pending'])
            ->where(function($q) use ($dayStart, $dayEnd) {
                $q->whereBetween('start_date', [$dayStart, $dayEnd])
                  ->orWhereBetween('end_date', [$dayStart, $dayEnd])
                  ->orWhere(function($q2) use ($dayStart, $dayEnd) {
                      $q2->where('start_date', '<=', $dayStart)
                         ->where('end_date', '>=', $dayEnd);
                  });
            })
            ->get();
            
        foreach ($bookingConflicts as $booking) {
            $conflicts->push([
                'start' => $booking->start_date,
                'end' => $booking->end_date,
                'type' => 'booking',
            ]);
        }
        
        return $conflicts->sortBy('start');
    }

    /**
     * Find available time gaps between conflicts.
     */
    private function findTimeGaps(Collection $conflicts, Carbon $dayStart, Carbon $dayEnd, int $minDurationHours): array
    {
        $gaps = [];
        $currentTime = $dayStart->copy();
        
        foreach ($conflicts as $conflict) {
            $conflictStart = Carbon::parse($conflict['start']);
            $conflictEnd = Carbon::parse($conflict['end']);
            
            // Check gap before this conflict
            if ($currentTime->lt($conflictStart)) {
                $gapDuration = $currentTime->diffInHours($conflictStart);
                if ($gapDuration >= $minDurationHours) {
                    $gaps[] = [
                        'start' => $currentTime->copy(),
                        'end' => $conflictStart->copy(),
                        'duration' => $gapDuration,
                    ];
                }
            }
            
            // Move current time to end of conflict
            if ($conflictEnd->gt($currentTime)) {
                $currentTime = $conflictEnd->copy();
            }
        }
        
        // Check gap after last conflict
        if ($currentTime->lt($dayEnd)) {
            $gapDuration = $currentTime->diffInHours($dayEnd);
            if ($gapDuration >= $minDurationHours) {
                $gaps[] = [
                    'start' => $currentTime->copy(),
                    'end' => $dayEnd->copy(),
                    'duration' => $gapDuration,
                ];
            }
        }
        
        return $gaps;
    }

    /**
     * Get vehicle availability status.
     */
    private function getVehicleAvailabilityStatus(Vehicle $vehicle, Carbon $startDate, Carbon $endDate): string
    {
        // Check for any conflicts in the requested period
        $hasConflicts = $vehicle->maintenanceSchedules()
            ->whereIn('status', ['scheduled', 'in_progress'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('scheduled_start', [$startDate, $endDate])
                  ->orWhereBetween('scheduled_end', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('scheduled_start', '<=', $startDate)
                         ->where('scheduled_end', '>=', $endDate);
                  });
            })
            ->exists();
            
        if ($hasConflicts) {
            return 'maintenance_scheduled';
        }
        
        $hasBookingConflicts = $vehicle->bookings()
            ->whereIn('status', ['approved', 'pending'])
            ->where(function($q) use ($startDate, $endDate) {
                $q->whereBetween('start_date', [$startDate, $endDate])
                  ->orWhereBetween('end_date', [$startDate, $endDate])
                  ->orWhere(function($q2) use ($startDate, $endDate) {
                      $q2->where('start_date', '<=', $startDate)
                         ->where('end_date', '>=', $endDate);
                  });
            })
            ->exists();
            
        if ($hasBookingConflicts) {
            return 'booked';
        }
        
        return 'available';
    }

    /**
     * Get time period label.
     */
    private function getTimePeriodLabel(Carbon $time): string
    {
        $hour = $time->hour;
        
        if ($hour >= 6 && $hour < 12) {
            return 'Morning';
        } elseif ($hour >= 12 && $hour < 17) {
            return 'Afternoon';
        } elseif ($hour >= 17 && $hour < 22) {
            return 'Evening';
        } else {
            return 'After Hours';
        }
    }
}