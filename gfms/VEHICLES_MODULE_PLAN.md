# Vehicles Management Module - Complete Implementation Plan

## Overview

Build a complete, production-ready Vehicles Management Module with all CRUD operations, modals, forms, and features before moving to other modules.

---

## Module Structure

```
src/
├── pages/
│   └── VehiclesPageV2.tsx ✅ (Done - List view with filters)
├── components/
│   └── vehicles/
│       ├── VehicleTable.tsx          (Extract table logic)
│       ├── VehicleFilters.tsx        (Extract filter logic)
│       ├── VehicleDetailsModal.tsx   (View vehicle details)
│       ├── VehicleFormModal.tsx      (Add/Edit vehicle)
│       ├── VehicleDeleteModal.tsx    (Delete confirmation)
│       └── VehicleStats.tsx          (Stats cards)
└── hooks/
    └── useVehicles.ts                (Vehicle data management)
```

---

## Features to Implement

### Phase 1: Modals & CRUD Operations (2-3 hours)

#### 1. View Vehicle Details Modal
- Display all vehicle information
- Show maintenance history
- Show booking history
- Show fuel consumption stats
- Close button

#### 2. Add Vehicle Modal
- Form with all vehicle fields:
  - Registration Number (required)
  - Make (required)
  - Model (required)
  - Year (required)
  - Fuel Type (dropdown: petrol, diesel, electric, hybrid)
  - Status (dropdown: active, maintenance, inactive)
  - Color
  - Mileage
  - Capacity (passengers)
  - Department/Organization
- Form validation
- Submit to API
- Success/error notifications
- Close on success

#### 3. Edit Vehicle Modal
- Pre-fill form with existing data
- Same fields as Add Vehicle
- Update API call
- Success/error notifications

#### 4. Delete Vehicle Modal
- Confirmation dialog
- Show vehicle details
- Delete API call
- Success/error notifications
- Remove from table on success

---

### Phase 2: Enhanced Features (1-2 hours)

#### 5. Vehicle Stats Cards
- Total Vehicles
- Active Vehicles
- In Maintenance
- Inactive Vehicles
- Average Mileage
- Fuel Efficiency

#### 6. Export Functionality
- Export to CSV
- Export to PDF
- Export filtered results
- Export all vehicles

#### 7. Bulk Actions
- Select multiple vehicles
- Bulk status update
- Bulk delete
- Bulk export

---

### Phase 3: Advanced Features (Optional - 2-3 hours)

#### 8. Vehicle Documents
- Upload documents (registration, insurance, etc.)
- View documents
- Download documents
- Delete documents

#### 9. Vehicle Timeline
- Show vehicle history
- Maintenance events
- Booking events
- Status changes
- Document uploads

#### 10. Vehicle QR Code
- Generate QR code for each vehicle
- Print QR code
- Scan QR code to view details

---

## Implementation Order (Recommended)

### Day 5: Core CRUD (2-3 hours)
1. ✅ VehiclesPageV2 with table and filters (Done)
2. Create VehicleDetailsModal
3. Create VehicleFormModal (Add/Edit)
4. Create VehicleDeleteModal
5. Wire up all modals to table actions
6. Test all CRUD operations
7. Commit progress

### Day 6: Stats & Polish (1-2 hours)
1. Create VehicleStats component
2. Add stats cards to VehiclesPageV2
3. Add export functionality
4. Polish UI/UX
5. Test everything
6. Commit progress

### Day 7: Advanced Features (Optional)
1. Add bulk actions
2. Add document management
3. Add vehicle timeline
4. Add QR code generation
5. Final testing
6. Commit progress

---

## API Endpoints Needed

### Vehicles
- `GET /api/vehicles` - List all vehicles ✅
- `GET /api/vehicles/:id` - Get vehicle details
- `POST /api/vehicles` - Create vehicle
- `PUT /api/vehicles/:id` - Update vehicle
- `DELETE /api/vehicles/:id` - Delete vehicle

### Stats
- `GET /api/vehicles/stats` - Get vehicle statistics

### Documents (Optional)
- `POST /api/vehicles/:id/documents` - Upload document
- `GET /api/vehicles/:id/documents` - List documents
- `DELETE /api/vehicles/:id/documents/:docId` - Delete document

---

## Component Breakdown

### VehicleDetailsModal
```typescript
interface VehicleDetailsModalProps {
  vehicle: Vehicle | null;
  open: boolean;
  onClose: () => void;
}
```

**Features:**
- Display all vehicle fields
- Show maintenance history (if available)
- Show booking history (if available)
- Edit button (opens VehicleFormModal)
- Delete button (opens VehicleDeleteModal)
- Close button

---

### VehicleFormModal
```typescript
interface VehicleFormModalProps {
  vehicle?: Vehicle; // undefined = Add, defined = Edit
  open: boolean;
  onClose: () => void;
  onSuccess: () => void;
}
```

**Features:**
- Form with all vehicle fields
- Validation (required fields, format checks)
- Submit button (Add or Update)
- Cancel button
- Loading state during submission
- Error handling
- Success notification

---

### VehicleDeleteModal
```typescript
interface VehicleDeleteModalProps {
  vehicle: Vehicle | null;
  open: boolean;
  onClose: () => void;
  onSuccess: () => void;
}
```

**Features:**
- Show vehicle details
- Confirmation message
- Delete button (danger style)
- Cancel button
- Loading state during deletion
- Error handling
- Success notification

---

### VehicleStats
```typescript
interface VehicleStatsProps {
  vehicles: Vehicle[];
}
```

**Features:**
- Calculate stats from vehicles array
- Display in Ant Design Statistic cards
- Color-coded by status
- Icons for each stat
- Responsive grid layout

---

## Form Fields

### Vehicle Form
```typescript
interface VehicleFormData {
  registration_number: string;    // Required, unique
  make: string;                    // Required
  model: string;                   // Required
  year: number;                    // Required, 1900-current year
  fuel_type: 'petrol' | 'diesel' | 'electric' | 'hybrid'; // Required
  status: 'active' | 'maintenance' | 'inactive'; // Required
  color?: string;                  // Optional
  mileage?: number;                // Optional
  capacity?: number;               // Optional (passengers)
  organization_id?: number;        // Optional
}
```

---

## Validation Rules

### Registration Number
- Required
- Unique
- Format: KXX 000X (Kenya format)
- Min length: 7
- Max length: 10

### Make & Model
- Required
- Min length: 2
- Max length: 50

### Year
- Required
- Min: 1900
- Max: Current year + 1

### Fuel Type
- Required
- One of: petrol, diesel, electric, hybrid

### Status
- Required
- One of: active, maintenance, inactive

### Mileage
- Optional
- Min: 0
- Max: 1,000,000

### Capacity
- Optional
- Min: 1
- Max: 100

---

## Success Criteria

### Phase 1 (Core CRUD)
- [ ] Can view vehicle details in modal
- [ ] Can add new vehicle
- [ ] Can edit existing vehicle
- [ ] Can delete vehicle
- [ ] All forms validate correctly
- [ ] API calls work
- [ ] Success/error notifications show
- [ ] Table refreshes after changes
- [ ] No console errors
- [ ] No TypeScript errors

### Phase 2 (Stats & Polish)
- [ ] Stats cards display correctly
- [ ] Stats update when vehicles change
- [ ] Export to CSV works
- [ ] Export to PDF works
- [ ] UI is polished and professional
- [ ] Mobile responsive

### Phase 3 (Advanced - Optional)
- [ ] Bulk actions work
- [ ] Document upload works
- [ ] Timeline displays correctly
- [ ] QR code generation works

---

## Testing Checklist

### Manual Testing
- [ ] Add vehicle with valid data
- [ ] Add vehicle with invalid data (should show errors)
- [ ] Edit vehicle
- [ ] Delete vehicle
- [ ] View vehicle details
- [ ] Filter vehicles by status
- [ ] Filter vehicles by fuel type
- [ ] Search vehicles
- [ ] Sort table columns
- [ ] Paginate through vehicles
- [ ] Test on mobile device
- [ ] Test with slow network (throttle)

### Edge Cases
- [ ] Add vehicle with duplicate registration
- [ ] Delete vehicle that's in use (booking)
- [ ] Edit vehicle while another user is editing
- [ ] Handle API errors gracefully
- [ ] Handle network timeout
- [ ] Handle empty state (no vehicles)

---

## Next Steps

### Start with Day 5 (Core CRUD)

**Step 1: Create VehicleDetailsModal (30 min)**
- Create component file
- Add modal with vehicle details
- Wire up to table "View" button
- Test

**Step 2: Create VehicleFormModal (1 hour)**
- Create component file
- Add form with all fields
- Add validation
- Wire up to "Add Vehicle" button
- Wire up to table "Edit" button
- Test add and edit

**Step 3: Create VehicleDeleteModal (30 min)**
- Create component file
- Add confirmation dialog
- Wire up to table "Delete" button
- Test delete

**Step 4: Test & Commit (30 min)**
- Test all CRUD operations
- Fix any bugs
- Commit progress

---

## Estimated Time

- **Phase 1 (Core CRUD):** 2-3 hours
- **Phase 2 (Stats & Polish):** 1-2 hours
- **Phase 3 (Advanced):** 2-3 hours (optional)

**Total:** 3-5 hours for complete module (5-8 hours with advanced features)

---

## Benefits of This Approach

1. ✅ **Complete one module at a time** - Easier to test and debug
2. ✅ **Reusable patterns** - Can copy structure to other modules
3. ✅ **Production-ready** - Full CRUD, not just list view
4. ✅ **Safe incremental progress** - Commit after each feature
5. ✅ **Easy rollback** - Can revert any feature if needed

---

**Status:** Ready to Start Phase 1  
**Next:** Create VehicleDetailsModal  
**Time:** 30 minutes  
**Risk:** 🟢 LOW
