<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create_bookings');
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
                'required',
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
                                
                                // Store conflict type for enhanced error response
                                $this->merge(['_conflict_type' => 'vehicle_status']);
                                $this->merge(['_vehicle_status' => $vehicle->status]);
                                
                                $fail($statusMessage);
                            }

                            // Check for maintenance schedule conflicts
                            if ($this->start_date && $this->end_date) {
                                $hasMaintenanceConflict = \App\Models\MaintenanceSchedule::hasConflict(
                                    $vehicle->id,
                                    $this->start_date,
                                    $this->end_date
                                );

                                if ($hasMaintenanceConflict) {
                                    $conflicts = \App\Models\MaintenanceSchedule::getConflicts(
                                        $vehicle->id,
                                        $this->start_date,
                                        $this->end_date
                                    );
                                    
                                    $conflictDetails = $conflicts->map(function($maintenance) {
                                        return $maintenance->type . ' (' . $maintenance->scheduled_start->format('M j, Y H:i') . ' - ' . $maintenance->scheduled_end->format('M j, Y H:i') . ')';
                                    })->join(', ');
                                    
                                    // Store conflict details for enhanced error response
                                    $this->merge(['_conflict_type' => 'maintenance']);
                                    $this->merge(['_conflict_details' => $conflictDetails]);
                                    
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
                    if ($value && $this->start_date && $this->end_date) {
                        // Check if driver is available
                        $isAvailable = \App\Models\DriverSchedule::isDriverAvailable(
                            $value,
                            $this->start_date,
                            $this->end_date
                        );

                        if (!$isAvailable) {
                            $conflicts = \App\Models\DriverSchedule::getDriverConflicts(
                                $value,
                                $this->start_date,
                                $this->end_date
                            );

                            $conflictDetails = $conflicts->map(function($conflict) {
                                return $conflict['reason'] . ' (' . 
                                    \Carbon\Carbon::parse($conflict['start'])->format('M j, H:i') . ' - ' . 
                                    \Carbon\Carbon::parse($conflict['end'])->format('M j, H:i') . ')';
                            })->join(', ');

                            $fail("Driver is not available during this period: {$conflictDetails}");
                        }

                        // Check working hours limit (max 60 hours per week)
                        $weeklyHours = \App\Models\DriverSchedule::getDriverWeeklyHours($value, $this->start_date);
                        $bookingHours = \Carbon\Carbon::parse($this->start_date)->diffInHours($this->end_date);
                        
                        if (($weeklyHours + $bookingHours) > 60) {
                            $fail("Driver would exceed maximum weekly working hours (60). Current: {$weeklyHours}h, Booking: {$bookingHours}h");
                        }
                    }
                },
            ],
            'start_date' => [
                'required',
                'date',
                'after_or_equal:today',
                function ($attribute, $value, $fail) {
                    $startDate = \Carbon\Carbon::parse($value);
                    $hoursFromNow = now()->diffInHours($startDate, false);
                    
                    // Require at least 2 hours advance booking (except for emergencies)
                    if ($hoursFromNow < 2) {
                        $fail('Bookings must be made at least 2 hours in advance for proper planning and vehicle preparation.');
                    }
                    
                    // Business hours validation (6 AM to 10 PM)
                    $startHour = $startDate->hour;
                    if ($startHour < 6 || $startHour >= 22) {
                        $fail('Bookings should start between 6:00 AM and 10:00 PM. For after-hours bookings, please provide justification in notes or contact fleet manager.');
                    }
                    
                    // Weekend booking warning (Friday evening to Monday morning)
                    if ($startDate->isFriday() && $startHour >= 18) {
                        // Weekend booking - could add special approval requirement
                    } elseif ($startDate->isWeekend()) {
                        // Weekend booking - could add special approval requirement  
                    }
                },
            ],
            'end_date' => [
                'required',
                'date',
                'after:start_date',
                function ($attribute, $value, $fail) {
                    if ($this->start_date) {
                        $startDate = \Carbon\Carbon::parse($this->start_date);
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
                        
                        // Warn for very long bookings (7+ days)
                        if ($durationDays >= 7) {
                            // This could trigger a flag for special approval in the future
                            // For now, we'll allow it but could add to notes
                        }
                    }
                },
            ],
            'purpose' => ['required', 'string', 'min:10', 'max:500'],
            'destination' => ['required', 'string', 'max:255'],
            'passengers' => [
                'required',
                'integer',
                'min:1',
                'max:100',
                function ($attribute, $value, $fail) {
                    if ($this->vehicle_id) {
                        $vehicle = \App\Models\Vehicle::find($this->vehicle_id);
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
            'vehicle_id.required' => 'Please select a vehicle',
            'vehicle_id.exists' => 'Selected vehicle does not exist',
            'start_date.required' => 'Start date is required',
            'start_date.after_or_equal' => 'Start date must be today or later',
            'end_date.required' => 'End date is required',
            'end_date.after' => 'End date must be after start date',
            'purpose.required' => 'Purpose is required',
            'purpose.min' => 'Purpose must be at least 10 characters',
            'destination.required' => 'Destination is required',
            'passengers.required' => 'Number of passengers is required',
            'passengers.min' => 'At least 1 passenger is required',
            'passengers.max' => 'Maximum 100 passengers allowed',
        ];
    }

    /**
     * Handle a failed validation attempt with conflict resolution suggestions.
     */
    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        $errors = $validator->errors();
        
        // Check if this is a conflict-related validation error
        $hasConflicts = $this->has('_conflict_type') && 
                       ($this->input('_conflict_type') === 'vehicle_status' || 
                        $this->input('_conflict_type') === 'maintenance' ||
                        $this->input('_conflict_type') === 'driver_unavailable');
        
        if ($hasConflicts && $this->vehicle_id && $this->start_date && $this->end_date) {
            try {
                $conflictResolutionService = app(\App\Services\ConflictResolutionService::class);
                $suggestions = $conflictResolutionService->getConflictResolutionSuggestions(
                    $this->vehicle_id,
                    $this->start_date,
                    $this->end_date,
                    $this->passengers,
                    $this->driver_id
                );
                
                $response = response()->json([
                    'message' => $errors->first(),
                    'errors' => $errors->toArray(),
                    'conflict_resolution' => [
                        'has_suggestions' => true,
                        'conflict_type' => $this->input('_conflict_type'),
                        'suggestions' => $suggestions,
                    ],
                ], 422);
            } catch (\Exception $e) {
                // Fallback to standard validation response if suggestions fail
                $response = response()->json([
                    'message' => $errors->first(),
                    'errors' => $errors->toArray(),
                    'conflict_resolution' => [
                        'has_suggestions' => false,
                        'error' => 'Could not generate suggestions',
                    ],
                ], 422);
            }
        } else {
            // Standard validation response for non-conflict errors
            $response = response()->json([
                'message' => $errors->first(),
                'errors' => $errors->toArray(),
            ], 422);
        }
        
        throw new \Illuminate\Http\Exceptions\HttpResponseException($response);
    }
}
