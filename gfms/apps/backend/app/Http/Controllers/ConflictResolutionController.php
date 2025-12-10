<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Services\ConflictResolutionService;
use App\Http\Requests\ConflictResolutionRequest;

class ConflictResolutionController extends Controller
{
    public function __construct(
        private ConflictResolutionService $conflictResolutionService
    ) {}

    /**
     * Get conflict resolution suggestions for a booking request.
     */
    public function getSuggestions(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'passengers' => 'nullable|integer|min:1',
            'driver_id' => 'nullable|exists:users,id',
        ]);

        try {
            $suggestions = $this->conflictResolutionService->getConflictResolutionSuggestions(
                $request->vehicle_id,
                $request->start_date,
                $request->end_date,
                $request->passengers,
                $request->driver_id
            );

            return response()->json([
                'success' => true,
                'data' => $suggestions,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get conflict resolution suggestions',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get alternative vehicles for a specific request.
     */
    public function getAlternativeVehicles(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'passengers' => 'nullable|integer|min:1',
            'limit' => 'nullable|integer|min:1|max:3',
        ]);

        try {
            $vehicle = \App\Models\Vehicle::findOrFail($request->vehicle_id);
            
            $alternatives = $this->conflictResolutionService->getSimilarAvailableVehicles(
                $vehicle,
                $request->start_date,
                $request->end_date,
                $request->passengers,
                $request->limit ?? 3
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'original_vehicle' => [
                        'id' => $vehicle->id,
                        'registration_number' => $vehicle->registration_number,
                        'make' => $vehicle->make,
                        'model' => $vehicle->model,
                        'capacity' => $vehicle->capacity,
                        'status' => $vehicle->status,
                    ],
                    'alternative_vehicles' => $alternatives,
                    'total_alternatives' => $alternatives->count(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get alternative vehicles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get alternative time slots for a vehicle.
     */
    public function getAlternativeTimeSlots(Request $request): JsonResponse
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'date' => 'required|date',
            'duration_hours' => 'required|integer|min:1|max:16',
            'limit' => 'nullable|integer|min:1|max:3',
        ]);

        try {
            $timeSlots = $this->conflictResolutionService->getAlternativeTimeSlots(
                $request->vehicle_id,
                $request->date,
                $request->duration_hours,
                $request->limit ?? 3
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'vehicle_id' => $request->vehicle_id,
                    'requested_date' => $request->date,
                    'duration_hours' => $request->duration_hours,
                    'alternative_slots' => $timeSlots,
                    'total_alternatives' => $timeSlots->count(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get alternative time slots',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get alternative drivers for a booking.
     */
    public function getAlternativeDrivers(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'limit' => 'nullable|integer|min:1|max:3',
        ]);

        try {
            $alternatives = $this->conflictResolutionService->getAlternativeDrivers(
                $request->driver_id,
                $request->start_date,
                $request->end_date,
                $request->limit ?? 3
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'original_driver_id' => $request->driver_id,
                    'alternative_drivers' => $alternatives,
                    'total_alternatives' => $alternatives->count(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get alternative drivers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
