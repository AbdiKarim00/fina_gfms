# Test Vehicles CRUD Operations

## Quick Test (10 minutes)

### Step 1: Start Dev Server
```bash
cd gfms/apps/frontend
npm run dev
```

### Step 2: Login
- Navigate to http://localhost:3000
- Personal Number: `123456`
- Password: `password`
- Enter OTP

### Step 3: Navigate to Vehicles
- Click "Vehicles" in sidebar
- Verify table displays vehicles

---

## Test Add Vehicle

### Step 1: Click "Add Vehicle" Button
- Top right corner
- Modal should open

### Step 2: Fill Required Fields
- **Registration Number**: GKB 999Z
- **Make**: Toyota
- **Model**: Land Cruiser
- **Year**: 2024
- **Fuel Type**: Diesel
- **Status**: Active

### Step 3: Fill Optional Fields
- **Engine Number**: 1GD-1234567
- **Chassis Number**: JTEBR3FJ70K123456
- **Color**: White
- **Mileage**: 5000
- **Capacity**: 7
- **Current Location**: POOL
- **Responsible Officer**: Test Officer
- **Has Log Book**: YES
- **Notes**: Test vehicle for CRUD operations

### Step 4: Submit
- Click "Add" button
- Should see success message
- Modal should close
- New vehicle should appear in table

---

## Test View Vehicle

### Step 1: Find Vehicle in Table
- Look for the vehicle you just added (GKB 999Z)

### Step 2: Click "View" Button (Eye Icon)
- Details modal should open
- Verify all fields display correctly
- Check engine number, chassis number
- Check location, responsible officer
- Check log book status
- Check notes

### Step 3: Close Modal
- Click "Close" button

---

## Test Edit Vehicle

### Step 1: Click "Edit" Button (Pencil Icon)
- Edit modal should open
- Form should be pre-filled with vehicle data

### Step 2: Modify Fields
- Change **Mileage** to 10000
- Change **Status** to Maintenance
- Change **Notes** to "Updated test vehicle"

### Step 3: Submit
- Click "Update" button
- Should see success message
- Modal should close
- Changes should reflect in table

---

## Test Delete Vehicle

### Step 1: Click "Delete" Button (Trash Icon)
- Delete confirmation modal should open
- Should show vehicle details
- Should show warning message

### Step 2: Confirm Deletion
- Click "Delete" button
- Should see success message
- Modal should close
- Vehicle should disappear from table

---

## Test Form Validation

### Step 1: Click "Add Vehicle"
- Leave all fields empty
- Click "Add"
- Should see validation errors

### Step 2: Test Registration Number
- Enter "ABC" (too short)
- Should see error: "Registration number must be at least 6 characters"

### Step 3: Test Year
- Enter 1980 (too old)
- Should see error: "Year must be 1990 or later"
- Enter 2030 (too far in future)
- Should see error about max year

### Step 4: Test Mileage
- Enter -100 (negative)
- Should see error: "Mileage cannot be negative"
- Enter 2000000 (too high)
- Should see error: "Mileage seems too high"

### Step 5: Test Capacity
- Enter 0 (too low)
- Should see error: "Capacity must be at least 1"
- Enter 150 (too high)
- Should see error: "Capacity cannot exceed 100"

---

## Test Filters with CRUD

### Step 1: Add Multiple Vehicles
- Add vehicle with status "Active"
- Add vehicle with status "Maintenance"
- Add vehicle with status "Inactive"

### Step 2: Test Status Filter
- Select "Active" from status filter
- Should only show active vehicles
- Select "Maintenance"
- Should only show maintenance vehicles

### Step 3: Test Search
- Type registration number in search
- Should filter results
- Type make or model
- Should filter results

### Step 4: Delete Filtered Vehicle
- With filter active, delete a vehicle
- Should remove from filtered view
- Clear filter
- Vehicle should be gone from all results

---

## Test Edge Cases

### Step 1: Duplicate Registration
- Try to add vehicle with existing registration
- Should see error message from API

### Step 2: Edit to Duplicate Registration
- Edit vehicle
- Change registration to existing one
- Should see error message

### Step 3: Delete Non-Existent Vehicle
- If API returns 404
- Should see error message

### Step 4: Network Error
- Disconnect internet (or throttle to offline)
- Try to add vehicle
- Should see error message
- Reconnect
- Try again, should work

---

## Expected Results

### Add Vehicle
- ✅ Modal opens
- ✅ Form validates correctly
- ✅ Success message shows
- ✅ Vehicle appears in table
- ✅ Table refreshes automatically

### View Vehicle
- ✅ Modal opens with correct data
- ✅ All fields display properly
- ✅ Edit/Delete buttons work

### Edit Vehicle
- ✅ Modal opens with pre-filled data
- ✅ Changes save correctly
- ✅ Table updates automatically

### Delete Vehicle
- ✅ Confirmation modal shows
- ✅ Warning message displays
- ✅ Vehicle details shown
- ✅ Deletion works
- ✅ Table updates automatically

### Validation
- ✅ Required fields enforced
- ✅ Min/max lengths enforced
- ✅ Number ranges enforced
- ✅ Error messages clear and helpful

---

## Troubleshooting

### Modal Doesn't Open
- Check console for errors
- Verify modal state management
- Check button onClick handlers

### Form Doesn't Submit
- Check console for errors
- Verify API endpoint
- Check network tab for request
- Verify backend is running

### Table Doesn't Refresh
- Check fetchVehicles() is called
- Verify API response
- Check console for errors

### Validation Not Working
- Check form rules
- Verify Ant Design Form setup
- Check console for errors

---

## Success Checklist

- [ ] Can add new vehicle
- [ ] Can view vehicle details
- [ ] Can edit existing vehicle
- [ ] Can delete vehicle
- [ ] Form validation works
- [ ] Success messages show
- [ ] Error messages show
- [ ] Table refreshes after operations
- [ ] Filters work with CRUD
- [ ] Search works with CRUD
- [ ] No console errors
- [ ] Mobile responsive

---

**Time:** 10-15 minutes  
**Risk:** 🟢 ZERO (easy rollback)  
**Status:** Ready for Testing
