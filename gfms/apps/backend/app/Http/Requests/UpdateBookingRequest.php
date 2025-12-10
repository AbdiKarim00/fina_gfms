<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $booking = $this->route('booking');
        
        // User can update their own pending bookings
        return $this->user()->id === $booking->requester_id && $booking->isPending();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'vehicle_id' => [
                'sometimes',
                'exists:vehicles,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $vehicle = \App\Models\Vehicle::find($value);
                        if ($vehicle) {
                            // Check vehicle status
                            if ($vehicle->status !== 'active') {
                                $statusMessage = match($vehicle->status) {
                                    'maintenance' => 'Vehicle is currently under maintenance and not available for booking.',
                                    'disposed' => 'Vehicle has been disposed and is no longer available.',
                                    'out_of_service' => 'Vehicle is out of service and requires major repairs.',
                                    default => "Vehicle status is '{$vehicle->status}' and not available for booking."
                                };
                                $fail($statusMessage);
                            }

                            // Check for maintenance schedule conflicts
                            $startDate = $this->start_date ?? $this->route('booking')->start_date;
                            $endDate = $this->end_date ?? $this->route('booking')->end_date;
                            
                            if ($startDate && $endDate) {
                                $hasMaintenanceConflict = \App\Models\MaintenanceSchedule::hasConflict(
                                    $vehicle->id,
                                    $startDate,
                                    $endDate,
                                    $this->route('booking')->id // Exclude current booking's maintenance if any
                                );

                                if ($hasMaintenanceConflict) {
                                    $conflicts = \App\Models\MaintenanceSchedule::getConflicts(
                                        $vehicle->id,
                                        $startDate,
                                        $endDate,
                                        $this->route('booking')->id
                                    );
                                    
                                    $conflictDetails = $conflicts->map(function($maintenance) {
                                        return $maintenance->type . ' (' . $maintenance->scheduled_start->format('M j, Y H:i') . ' - ' . $maintenance->scheduled_end->format('M j, Y H:i') . ')';
                                    })->join(', ');
                                    
                                    $fail("Vehicle has scheduled maintenance during this period: {$conflictDetails}");
                                }
                            }
                        }
                    }
                },
            ],
            'driver_id' => [
                'nullable',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $startDate = $this->start_date ?? $this->route('booking')->start_date;
                        $endDate = $this->end_date ?? $this->route('booking')->end_date;
                        
                        if ($startDate && $endDate) {
                            // Check if driver is available
                            $isAvailable = \App\Models\DriverSchedule::isDriverAvailable(
                                $value,
                                $startDate,
                                $endDate,
                                $this->route('booking')->id
                            );

                            if (!$isAvailable) {
                                $conflicts = \App\Models\DriverSchedule::getDriverConflicts(
                                    $value,
                                    $startDate,
                                    $endDate,
                                    $this->route('booking')->id
                                );

                                $conflictDetails = $conflicts->map(function($conflict) {
                                    return $conflict['reason'] . ' (' . 
                                        \Carbon\Carbon::parse($conflict['start'])->format('M j, H:i') . ' - ' . 
                                        \Carbon\Carbon::parse($conflict['end'])->format('M j, H:i') . ')';
                                })->join(', ');

                                $fail("Driver is not available during this period: {$conflictDetails}");
                            }

                            // Check working hours limit (max 60 hours per week)
                            $weeklyHours = \App\Models\DriverSchedule::getDriverWeeklyHours($value, $startDate);
                            $bookingHours = \Carbon\Carbon::parse($startDate)->diffInHours($endDate);
                            
                            if (($weeklyHours + $bookingHours) > 60) {
                                $fail("Driver would exceed maximum weekly working hours (60). Current: {$weeklyHours}h, Booking: {$bookingHours}h");
                            }
                        }
                    }
                },
            ],
            'start_date' => [
                'sometimes',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $startDate = \Carbon\Carbon::parse($value);
                        $hoursFromNow = now()->diffInHours($startDate, false);
                        
                        // Require at least 2 hours advance booking
                        if ($hoursFromNow < 2) {
                            $fail('Bookings must be made at least 2 hours in advance for proper planning and vehicle preparation.');
                        }
                        
                        // Business hours validation (6 AM to 10 PM)
                        $startHour = $startDate->hour;
                        if ($startHour < 6 || $startHour >= 22) {
                            $fail('Bookings should start between 6:00 AM and 10:00 PM. For after-hours bookings, please provide justification in notes.');
                        }
                    }
                },
            ],
            'end_date' => [
                'sometimes',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    $startDate = $this->start_date ?? $this->route('booking')->start_date;
                    if ($value && $startDate) {
                        $startDate = \Carbon\Carbon::parse($startDate);
                        $endDate = \Carbon\Carbon::parse($value);
                        $durationDays = $startDate->diffInDays($endDate);
                        $durationHours = $startDate->diffInHours($endDate);
                        
                        // Maximum 30 days booking duration
                        if ($durationDays > 30) {
                            $fail('Maximum booking duration is 30 days. Please split longer requirements into multiple bookings.');
                        }
                        
                        // Minimum 1 hour booking
                        if ($durationHours < 1) {
                            $fail('Minimum booking duration is 1 hour.');
                        }
                        
                        // Business hours validation for end time
                        $endHour = $endDate->hour;
                        if ($endHour > 22 || $endHour < 6) {
                            $fail('Bookings should end between 6:00 AM and 10:00 PM. For after-hours returns, please provide justification in notes.');
                        }
                    }
                },
            ],
            'purpose' => ['sometimes', 'string', 'min:10', 'max:500'],
            'destination' => ['sometimes', 'string', 'max:255'],
            'passengers' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) {
                    $vehicleId = $this->vehicle_id ?? $this->route('booking')->vehicle_id;
                    if ($value && $vehicleId) {
                        $vehicle = \App\Models\Vehicle::find($vehicleId);
                        if ($vehicle && $vehicle->capacity && $value > $vehicle->capacity) {
                            $fail("Vehicle capacity is {$vehicle->capacity} passengers. You requested {$value} passengers.");
                        }
                    }
                },
            ],
            'priority' => ['sometimes', 'in:high,medium,low'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'vehicle_id.exists' => 'Selected vehicle does not exist',
            'start_date.after_or_equal' => 'Start date must be today or later',
            'end_date.after' => 'End date must be after start date',
            'purpose.min' => 'Purpose must be at least 10 characters',
            'passengers.min' => 'At least 1 passenger is required',
            'passengers.max' => 'Maximum 100 passengers allowed',
        ];
    }
}
