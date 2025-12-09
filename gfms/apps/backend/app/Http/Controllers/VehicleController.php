<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use App\Services\VehicleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    protected VehicleService $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    /**
     * Display a listing of vehicles.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = [
                'status' => $request->query('status'),
                'fuel_type' => $request->query('fuel_type'),
                'search' => $request->query('search'),
                'organization_id' => $request->query('organization_id'),
            ];

            // Remove null filters
            $filters = array_filter($filters, fn($value) => !is_null($value));

            $vehicles = $this->vehicleService->getAllVehicles($filters);

            return response()->json([
                'success' => true,
                'message' => 'Vehicles retrieved successfully',
                'data' => $vehicles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created vehicle.
     */
    public function store(StoreVehicleRequest $request): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->createVehicle($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Vehicle created successfully',
                'data' => $vehicle,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vehicle',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified vehicle.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->getVehicleById($id);

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle retrieved successfully',
                'data' => $vehicle,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve vehicle',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified vehicle.
     */
    public function update(UpdateVehicleRequest $request, int $id): JsonResponse
    {
        try {
            $vehicle = $this->vehicleService->updateVehicle($id, $request->validated());

            if (!$vehicle) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle updated successfully',
                'data' => $vehicle,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vehicle',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified vehicle.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $deleted = $this->vehicleService->deleteVehicle($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehicle not found',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Vehicle deleted successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vehicle',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get vehicle statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->vehicleService->getStatistics();

            return response()->json([
                'success' => true,
                'message' => 'Statistics retrieved successfully',
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve statistics',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk update vehicles.
     */
    public function bulkUpdate(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:vehicles,id',
                'data' => 'required|array',
            ]);

            $count = $this->vehicleService->bulkUpdate($request->ids, $request->data);

            return response()->json([
                'success' => true,
                'message' => "{$count} vehicles updated successfully",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk update vehicles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Bulk delete vehicles.
     */
    public function bulkDelete(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'ids' => 'required|array',
                'ids.*' => 'integer|exists:vehicles,id',
            ]);

            $count = $this->vehicleService->bulkDelete($request->ids);

            return response()->json([
                'success' => true,
                'message' => "{$count} vehicles deleted successfully",
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk delete vehicles',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
