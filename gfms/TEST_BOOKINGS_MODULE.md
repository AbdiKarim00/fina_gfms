# Test Bookings Module - Day 2

**Date**: December 9, 2025  
**Status**: Ready for Testing

---

## 🚀 Quick Start

### Services Running
- **Frontend**: http://localhost:3000
- **Backend**: http://localhost:8000
- **Database**: PostgreSQL on port 5433

---

## 🧪 Test Scenarios

### Test 1: Fleet Manager - View Bookings Queue

**Login Credentials**: `234567` / `password`

**Expected Behavior**:
1. Navigate to "Bookings" from sidebar
2. See statistics cards:
   - Total Bookings
   - Pending Approval (should show 3)
   - Approved (should show 1)
   - Rejected (should show 1)
3. See booking cards with:
   - Priority ribbons (HIGH/MEDIUM/LOW)
   - Status tags
   - Vehicle info
   - Requester name
   - Destination
   - Date/time with duration
   - "2 hours ago" relative time
4. See Approve/Reject buttons on pending bookings

**Actions to Test**:
- ✅ Filter by status (Pending, Approved, Rejected, Completed)
- ✅ Filter by priority (High, Medium, Low)
- ✅ Search by destination, purpose, vehicle, or requester
- ✅ Click "View Details" to see full booking info
- ✅ Click "Approve" on a pending booking
- ✅ Click "Reject" and provide a reason (min 10 chars)
- ✅ Click "Refresh" to reload data

---

### Test 2: Admin - Same as Fleet Manager

**Login Credentials**: `123456` / `password`

**Expected Behavior**: Same as Fleet Manager (has approve_bookings permission)

---

### Test 3: Transport Officer - Limited View

**Login Credentials**: `345678` / `password`

**Expected Behavior**:
1. Navigate to "My Bookings" from sidebar
2. Should see bookings page
3. Should NOT see Approve/Reject buttons (no approve_bookings permission)
4. Can only view booking details

---

### Test 4: Driver - No Access

**Login Credentials**: `654321` / `password`

**Expected Behavior**:
1. Should NOT see "Bookings" in sidebar
2. If manually navigating to /bookings, should see "You don't have permission" message

---

## 📊 Sample Data (from BookingSeeder)

### Pending Bookings (3)
1. **High Priority** - Nairobi to Mombasa
   - Vehicle: KAA 001A
   - Requester: Admin User
   - Duration: 3 days

2. **Medium Priority** - Nairobi to Kisumu
   - Vehicle: KAB 002B
   - Requester: Fleet Manager
   - Duration: 2 days

3. **Low Priority** - Nairobi to Nakuru
   - Vehicle: KAC 003C
   - Requester: Transport Officer
   - Duration: 1 day

### Approved (1)
- Nairobi to Eldoret (KAD 004D)

### Rejected (1)
- Nairobi to Malindi (KAE 005E)
- Reason: "Vehicle not available for the requested dates"

### Completed (1)
- Nairobi to Thika (KAF 006F)

---

## 🔍 What to Check

### UI/UX
- [ ] Statistics cards display correct counts
- [ ] Priority ribbons show correct colors (red=high, orange=medium, blue=low)
- [ ] Status tags show correct colors (warning=pending, success=approved, error=rejected)
- [ ] Relative time displays correctly ("2 hours ago")
- [ ] Duration calculation is accurate
- [ ] Cards are responsive on mobile
- [ ] Filters work correctly
- [ ] Search is case-insensitive and searches all fields

### Functionality
- [ ] Approve button works and updates UI
- [ ] Reject modal opens and requires reason
- [ ] Reject reason must be at least 10 characters
- [ ] After approve/reject, statistics update
- [ ] After approve/reject, booking moves to correct status
- [ ] Details modal shows all booking information
- [ ] Refresh button reloads data

### Permissions (RBAC)
- [ ] Fleet Manager can approve/reject
- [ ] Admin can approve/reject
- [ ] Transport Officer cannot approve/reject (buttons hidden)
- [ ] Driver cannot access bookings page

### API Integration
- [ ] GET /api/bookings/pending returns pending bookings
- [ ] GET /api/bookings/statistics returns correct counts
- [ ] POST /api/bookings/{id}/approve works
- [ ] POST /api/bookings/{id}/reject works with reason
- [ ] Filters send correct query parameters

---

## 🐛 Known Issues / TODO

- [ ] Need to create MyBookingsPage for Transport Officer (create/edit own bookings)
- [ ] Need to create BookingFormModal for creating new bookings
- [ ] Need to add conflict detection (check if vehicle is already booked)
- [ ] Need to add calendar view
- [ ] Need to add bulk approve/reject
- [ ] Need to add email notifications on approval/rejection

---

## 📝 Testing Checklist

### Phase 1: Basic Display ✅
- [x] Page loads without errors
- [x] Statistics cards display
- [x] Booking cards render
- [x] Filters render
- [x] Search box works

### Phase 2: Interactions (Current)
- [ ] Approve booking
- [ ] Reject booking with reason
- [ ] View details modal
- [ ] Filter by status
- [ ] Filter by priority
- [ ] Search functionality
- [ ] Refresh data

### Phase 3: Permissions
- [ ] Test with Fleet Manager
- [ ] Test with Admin
- [ ] Test with Transport Officer
- [ ] Test with Driver

### Phase 4: Edge Cases
- [ ] Empty state (no bookings)
- [ ] Long destination names
- [ ] Long rejection reasons
- [ ] Network errors
- [ ] Invalid booking IDs

---

## 🎯 Next Steps

After testing Phase 2:
1. Create MyBookingsPage for Transport Officer
2. Create BookingFormModal for creating/editing bookings
3. Add conflict detection
4. Add calendar view
5. Test end-to-end workflow

---

**Ready to Test!** 🚀

Open http://localhost:3000 and login with Fleet Manager credentials (234567/password)
