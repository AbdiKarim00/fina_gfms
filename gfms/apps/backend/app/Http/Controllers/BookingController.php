<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Http\Requests\ApproveBookingRequest;
use App\Http\Requests\RejectBookingRequest;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private BookingService $bookingService
    ) {}

    /**
     * Get all bookings (with filters).
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['status', 'priority', 'vehicle_id', 'requester_id', 'start_date', 'end_date']);
            $perPage = $request->input('per_page', 15);

            $bookings = $this->bookingService->getAllBookings($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => $bookings->items(),
                'meta' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ],
            ]);
        } catch (\Exception $e) {
            \Log::error('Bookings index error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch bookings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get pending bookings for approval (Fleet Manager).
     */
    public function pending(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $bookings = $this->bookingService->getPendingBookings($perPage);

            return response()->json([
                'success' => true,
                'data' => $bookings->items(),
                'meta' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch pending bookings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get user's own bookings.
     */
    public function myBookings(Request $request): JsonResponse
    {
        try {
            $perPage = $request->input('per_page', 15);
            $bookings = $this->bookingService->getUserBookings($request->user()->id, $perPage);

            return response()->json([
                'success' => true,
                'data' => $bookings->items(),
                'meta' => [
                    'current_page' => $bookings->currentPage(),
                    'last_page' => $bookings->lastPage(),
                    'per_page' => $bookings->perPage(),
                    'total' => $bookings->total(),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch your bookings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get bookings for calendar view.
     */
    public function calendar(Request $request): JsonResponse
    {
        try {
            $startDate = $request->input('start_date', now()->startOfMonth());
            $endDate = $request->input('end_date', now()->endOfMonth());

            $bookings = $this->bookingService->getCalendarBookings($startDate, $endDate);

            return response()->json([
                'success' => true,
                'data' => $bookings,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch calendar bookings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get a specific booking.
     */
    public function show(Booking $booking): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $booking->load(['vehicle', 'requester', 'driver', 'approver']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch booking',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Create a new booking.
     */
    public function store(StoreBookingRequest $request): JsonResponse
    {
        try {
            $booking = $this->bookingService->createBooking(
                $request->validated(),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking created successfully',
                'data' => $booking->load(['vehicle', 'requester']),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 400);
        }
    }

    /**
     * Update a booking.
     */
    public function update(UpdateBookingRequest $request, Booking $booking): JsonResponse
    {
        try {
            $this->bookingService->updateBooking($booking, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Booking updated successfully',
                'data' => $booking->fresh()->load(['vehicle', 'requester', 'driver']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 400);
        }
    }

    /**
     * Approve a booking.
     */
    public function approve(ApproveBookingRequest $request, Booking $booking): JsonResponse
    {
        try {
            $this->bookingService->approveBooking($booking, $request->user());

            return response()->json([
                'success' => true,
                'message' => 'Booking approved successfully',
                'data' => $booking->fresh()->load(['vehicle', 'requester', 'driver', 'approver']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 400);
        }
    }

    /**
     * Reject a booking.
     */
    public function reject(RejectBookingRequest $request, Booking $booking): JsonResponse
    {
        try {
            $this->bookingService->rejectBooking(
                $booking,
                $request->user(),
                $request->input('reason')
            );

            return response()->json([
                'success' => true,
                'message' => 'Booking rejected successfully',
                'data' => $booking->fresh()->load(['vehicle', 'requester', 'approver']),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 400);
        }
    }

    /**
     * Cancel a booking.
     */
    public function cancel(Booking $booking): JsonResponse
    {
        try {
            // Check authorization
            if ($booking->requester_id !== request()->user()->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized to cancel this booking',
                ], 403);
            }

            $this->bookingService->cancelBooking($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking cancelled successfully',
                'data' => $booking->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 400);
        }
    }

    /**
     * Check for booking conflicts.
     */
    public function checkConflicts(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'vehicle_id' => 'required|exists:vehicles,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'exclude_booking_id' => 'nullable|exists:bookings,id',
            ]);

            $conflicts = $this->bookingService->checkConflicts(
                $request->input('vehicle_id'),
                $request->input('start_date'),
                $request->input('end_date'),
                $request->input('exclude_booking_id')
            );

            return response()->json([
                'success' => true,
                'has_conflicts' => $conflicts->isNotEmpty(),
                'conflicts' => $conflicts,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to check conflicts',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get available vehicles for a date range.
     */
    public function availableVehicles(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
            ]);

            $vehicles = $this->bookingService->getAvailableVehicles(
                $request->input('start_date'),
                $request->input('end_date')
            );

            return response()->json([
                'success' => true,
                'data' => $vehicles,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch available vehicles',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get booking statistics.
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->bookingService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ]);
        } catch (\Exception $e) {
            \Log::error('Bookings statistics error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Bulk approve bookings.
     */
    public function bulkApprove(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'booking_ids' => 'required|array',
                'booking_ids.*' => 'exists:bookings,id',
            ]);

            $results = $this->bookingService->bulkApprove(
                $request->input('booking_ids'),
                $request->user()
            );

            return response()->json([
                'success' => true,
                'message' => 'Bulk approval completed',
                'data' => $results,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to bulk approve bookings',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
