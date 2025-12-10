# Booking Conflict Detection System

## Overview
The system implements comprehensive conflict detection to prevent double-booking of vehicles.

---

## How Conflict Detection Works

### 1. **Scope Definition** (Booking Model)

```php
public function scopeConflicts($query, int $vehicleId, $startDate, $endDate, ?int $excludeBookingId = null)
{
    $query = $query->where('vehicle_id', $vehicleId)
                   ->whereIn('status', ['pending', 'approved'])  // Only check active bookings
                   ->where(function ($q) use ($startDate, $endDate) {
                       // Case 1: New booking starts during existing booking
                       $q->whereBetween('start_date', [$startDate, $endDate])
                         // Case 2: New booking ends during existing booking
                         ->orWhereBetween('end_date', [$startDate, $endDate])
                         // Case 3: New booking completely encompasses existing booking
                         ->orWhere(function ($q2) use ($startDate, $endDate) {
                             $q2->where('start_date', '<=', $startDate)
                                ->where('end_date', '>=', $endDate);
                         });
                   });

    if ($excludeBookingId) {
        $query->where('id', '!=', $excludeBookingId);  // Exclude current booking when updating
    }

    return $query;
}
```

### 2. **Conflict Scenarios Detected**

#### Scenario A: Overlap at Start
```
Existing:  |---------|
New:           |---------|
Result: CONFLICT ❌
```

#### Scenario B: Overlap at End
```
Existing:      |---------|
New:       |---------|
Result: CONFLICT ❌
```

#### Scenario C: Complete Overlap
```
Existing:    |-----|
New:       |---------|
Result: CONFLICT ❌
```

#### Scenario D: Contained Within
```
Existing:  |---------|
New:         |---|
Result: CONFLICT ❌
```

#### Scenario E: No Overlap
```
Existing:  |-----|
New:                |-----|
Result: OK ✅
```

---

## When Conflicts Are Checked

### 1. **During Booking Creation**
```php
// In BookingService::createBooking()
$conflicts = $this->checkConflicts(
    $data['vehicle_id'],
    $data['start_date'],
    $data['end_date']
);

if ($conflicts->isNotEmpty()) {
    throw new \Exception('Vehicle is already booked for the selected time period');
}
```

### 2. **During Booking Approval**
```php
// In BookingService::approveBooking()
$conflicts = $this->checkConflicts(
    $booking->vehicle_id,
    $booking->start_date,
    $booking->end_date,
    $booking->id  // Exclude current booking
);

if ($conflicts->isNotEmpty()) {
    throw new \Exception('Vehicle is already booked for the selected time period');
}
```

### 3. **During Booking Update**
```php
// In BookingService::updateBooking()
if (isset($data['start_date']) || isset($data['end_date']) || isset($data['vehicle_id'])) {
    $conflicts = $this->checkConflicts(
        $data['vehicle_id'] ?? $booking->vehicle_id,
        $data['start_date'] ?? $booking->start_date,
        $data['end_date'] ?? $booking->end_date,
        $booking->id
    );

    if ($conflicts->isNotEmpty()) {
        throw new \Exception('Vehicle is already booked for the selected time period');
    }
}
```

---

## Status Considerations

### Statuses That Block Conflicts
- ✅ **pending** - Awaiting approval
- ✅ **approved** - Confirmed booking

### Statuses That Don't Block
- ❌ **rejected** - Declined booking
- ❌ **cancelled** - User cancelled
- ❌ **completed** - Past booking (finished)

**Rationale:** Only active or potentially active bookings should prevent new bookings.

---

## API Endpoints for Conflict Checking

### 1. Check Conflicts Endpoint
```http
POST /api/v1/bookings/check-conflicts
Content-Type: application/json

{
  "vehicle_id": 1,
  "start_date": "2024-12-10 09:00:00",
  "end_date": "2024-12-10 17:00:00",
  "exclude_booking_id": 5  // Optional: when updating
}
```

**Response:**
```json
{
  "success": true,
  "has_conflicts": true,
  "conflicts": [
    {
      "id": 3,
      "vehicle_id": 1,
      "start_date": "2024-12-10 08:00:00",
      "end_date": "2024-12-10 12:00:00",
      "status": "approved",
      "requester": {
        "id": 2,
        "name": "John Doe"
      }
    }
  ]
}
```

### 2. Available Vehicles Endpoint
```http
POST /api/v1/bookings/available-vehicles
Content-Type: application/json

{
  "start_date": "2024-12-10 09:00:00",
  "end_date": "2024-12-10 17:00:00"
}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "registration_number": "KBZ 123A",
      "make": "Toyota",
      "model": "Land Cruiser",
      "status": "active"
    }
  ]
}
```

---

## Error Messages

### User-Friendly Messages

| Scenario | Message |
|----------|---------|
| Conflict detected | "Vehicle is already booked for the selected time period" |
| Not pending | "Only pending bookings can be approved" |
| Already approved | "This booking has already been approved" |
| Already rejected | "This booking has already been rejected" |

---

## Frontend Integration

### Error Handling
```typescript
const handleApprove = async (bookingId: number) => {
  try {
    await apiClient.post(`/bookings/${bookingId}/approve`);
    message.success('Booking approved successfully! 🎉');
  } catch (error: any) {
    if (error.response?.status === 400) {
      // Show backend error message
      const errorMsg = error.response?.data?.message;
      message.error(errorMsg, 6);
    }
  }
};
```

---

## Improvements Needed

### 1. **Proactive Conflict Warning**
Show conflicts before user tries to approve:
```typescript
// In BookingCard component
const [conflicts, setConflicts] = useState([]);

useEffect(() => {
  if (booking.status === 'pending') {
    checkConflicts(booking);
  }
}, [booking]);

// Show warning badge if conflicts exist
{conflicts.length > 0 && (
  <Badge count={conflicts.length} status="warning">
    <WarningOutlined /> Conflicts detected
  </Badge>
)}
```

### 2. **Conflict Resolution UI**
Allow managers to:
- View conflicting bookings
- Compare priorities
- Reassign to different vehicle
- Adjust time slots

### 3. **Real-time Conflict Detection**
Use WebSockets to notify when conflicts arise:
```typescript
// Listen for new bookings that create conflicts
socket.on('booking:conflict', (data) => {
  message.warning(`New conflict detected for ${data.vehicle}`);
  fetchBookings();
});
```

### 4. **Smart Suggestions**
When conflict detected, suggest:
- Alternative vehicles
- Alternative time slots
- Split booking into multiple slots

---

## Testing Scenarios

### Test Case 1: Exact Overlap
```
Booking A: 2024-12-10 09:00 - 17:00
Booking B: 2024-12-10 09:00 - 17:00
Expected: CONFLICT
```

### Test Case 2: Partial Overlap (Start)
```
Booking A: 2024-12-10 09:00 - 13:00
Booking B: 2024-12-10 12:00 - 16:00
Expected: CONFLICT
```

### Test Case 3: Partial Overlap (End)
```
Booking A: 2024-12-10 12:00 - 16:00
Booking B: 2024-12-10 09:00 - 13:00
Expected: CONFLICT
```

### Test Case 4: Contained
```
Booking A: 2024-12-10 09:00 - 17:00
Booking B: 2024-12-10 11:00 - 13:00
Expected: CONFLICT
```

### Test Case 5: No Overlap
```
Booking A: 2024-12-10 09:00 - 12:00
Booking B: 2024-12-10 13:00 - 16:00
Expected: NO CONFLICT
```

### Test Case 6: Back-to-Back
```
Booking A: 2024-12-10 09:00 - 12:00
Booking B: 2024-12-10 12:00 - 16:00
Expected: CONFLICT (same end/start time)
```

---

## Database Indexes for Performance

```sql
-- Optimize conflict queries
CREATE INDEX idx_bookings_vehicle_dates ON bookings(vehicle_id, start_date, end_date);
CREATE INDEX idx_bookings_status ON bookings(status);
CREATE INDEX idx_bookings_vehicle_status ON bookings(vehicle_id, status);
```

---

## Summary

✅ **Comprehensive conflict detection** across all overlap scenarios
✅ **Multiple checkpoints** (create, update, approve)
✅ **Status-aware** (only checks active bookings)
✅ **User-friendly error messages**
✅ **API endpoints** for proactive checking

🔄 **Future Enhancements:**
- Proactive conflict warnings in UI
- Conflict resolution workflow
- Real-time notifications
- Smart alternative suggestions
