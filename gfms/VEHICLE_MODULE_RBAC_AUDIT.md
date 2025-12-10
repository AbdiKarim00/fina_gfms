# Vehicle Module RBAC Audit & Implementation Plan

**Date**: December 9, 2025  
**Issue**: Vehicle module showing same UI/permissions to all roles  
**Status**: AUDIT COMPLETE - IMPLEMENTATION NEEDED

---

## 🔍 Current State Analysis

### Problem
The VehiclesPageV2 component shows the same interface to all users:
- ❌ All roles see "Add Vehicle" button
- ❌ All roles see Edit/Delete actions
- ❌ No permission checks in UI
- ❌ Same description for all roles

### Current Access (from roleMenus.tsx)
| Role | Has Menu Item | Label |
|------|--------------|-------|
| Super Admin | ✅ Yes | "Vehicles" |
| Admin | ✅ Yes | "Vehicles" |
| Fleet Manager | ✅ Yes | "Vehicles" |
| Transport Officer | ✅ Yes | "Available Vehicles" |
| Driver | ❌ No | N/A |

---

## 🎯 Backend Permissions (Already Defined)

### Super Admin
- ✅ view_vehicles
- ✅ create_vehicles
- ✅ edit_vehicles
- ✅ delete_vehicles
- ✅ assign_vehicles

### Admin
- ✅ view_vehicles
- ✅ create_vehicles
- ✅ edit_vehicles
- ✅ delete_vehicles
- ✅ assign_vehicles

### Fleet Manager
- ✅ view_vehicles
- ✅ create_vehicles
- ✅ edit_vehicles
- ✅ assign_vehicles
- ❌ delete_vehicles (NO DELETE)

### Transport Officer
- ✅ view_vehicles
- ✅ edit_vehicles (limited - for booking purposes)
- ❌ create_vehicles
- ❌ delete_vehicles
- ❌ assign_vehicles

### Driver
- ✅ view_vehicles (read-only)
- ❌ create_vehicles
- ❌ edit_vehicles
- ❌ delete_vehicles
- ❌ assign_vehicles

---

## 📋 Recommended UI Permissions

### 1. Super Admin (Full Access)
**UI Elements**:
- ✅ View vehicle list
- ✅ View statistics
- ✅ Search & filter
- ✅ "Add Vehicle" button
- ✅ View details
- ✅ Edit button
- ✅ Delete button
- ✅ All vehicle fields editable

**Description**: "Manage all fleet vehicles with full administrative access"

---

### 2. Admin (Full Access)
**UI Elements**:
- ✅ View vehicle list
- ✅ View statistics
- ✅ Search & filter
- ✅ "Add Vehicle" button
- ✅ View details
- ✅ Edit button
- ✅ Delete button
- ✅ All vehicle fields editable

**Description**: "Manage your organization's fleet vehicles"

---

### 3. Fleet Manager (Manage, No Delete)
**UI Elements**:
- ✅ View vehicle list
- ✅ View statistics
- ✅ Search & filter
- ✅ "Add Vehicle" button
- ✅ View details
- ✅ Edit button
- ❌ Delete button (HIDDEN)
- ✅ All vehicle fields editable

**Description**: "Manage fleet vehicles including registration and maintenance"

**Note**: Can add and edit vehicles but cannot delete (requires admin approval)

---

### 4. Transport Officer (View & Limited Edit)
**UI Elements**:
- ✅ View vehicle list
- ✅ View statistics (availability focused)
- ✅ Search & filter
- ❌ "Add Vehicle" button (HIDDEN)
- ✅ View details
- ✅ Edit button (limited fields only)
- ❌ Delete button (HIDDEN)
- ⚠️ Limited fields editable (status, notes for booking)

**Description**: "View available vehicles and check availability for bookings"

**Editable Fields**:
- Status (for booking purposes)
- Notes (add booking-related notes)

**Read-Only Fields**:
- Registration, Make, Model, Year
- Engine Number, Chassis Number
- Mileage, Capacity
- All other technical details

---

### 5. Driver (Read-Only)
**UI Elements**:
- ✅ View vehicle list (assigned vehicles only)
- ❌ View statistics (HIDDEN)
- ✅ Search (limited)
- ❌ "Add Vehicle" button (HIDDEN)
- ✅ View details (read-only)
- ❌ Edit button (HIDDEN)
- ❌ Delete button (HIDDEN)
- ❌ All fields read-only

**Description**: "View your assigned vehicles and their details"

**Special Behavior**:
- Should only see vehicles assigned to them
- Cannot see all fleet vehicles
- Read-only access to vehicle information

---

## 🔧 Implementation Plan

### Phase 1: Add Permission Context
1. Create `usePermissions` hook
2. Check user permissions from AuthContext
3. Provide permission checking functions

### Phase 2: Update VehiclesPageV2
1. Add permission checks for buttons
2. Conditionally render Add/Edit/Delete buttons
3. Update page description based on role
4. Filter vehicles for drivers (assigned only)

### Phase 3: Update Vehicle Modals
1. VehicleFormModal - disable fields based on role
2. VehicleDetailsModal - hide Edit/Delete for restricted roles
3. VehicleDeleteModal - only show for authorized roles

### Phase 4: Update Statistics
1. Show different stats based on role
2. Transport Officer - focus on availability
3. Driver - show assigned vehicle stats only

---

## 📊 Permission Matrix

| Action | Super Admin | Admin | Fleet Mgr | Transport Officer | Driver |
|--------|-------------|-------|-----------|------------------|--------|
| **View List** | All | All | All | All | Assigned Only |
| **View Details** | ✅ | ✅ | ✅ | ✅ | ✅ (Read-only) |
| **Add Vehicle** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Edit All Fields** | ✅ | ✅ | ✅ | ❌ | ❌ |
| **Edit Limited** | N/A | N/A | N/A | ✅ (Status/Notes) | ❌ |
| **Delete Vehicle** | ✅ | ✅ | ❌ | ❌ | ❌ |
| **View Statistics** | ✅ | ✅ | ✅ | ✅ (Availability) | ❌ |
| **Assign Vehicles** | ✅ | ✅ | ✅ | ❌ | ❌ |

---

## 🎨 UI Changes by Role

### Super Admin & Admin
```
[Add Vehicle Button] - Visible
[Edit Icon] - Visible on all rows
[Delete Icon] - Visible on all rows
Description: "Manage all fleet vehicles with full administrative access"
```

### Fleet Manager
```
[Add Vehicle Button] - Visible
[Edit Icon] - Visible on all rows
[Delete Icon] - HIDDEN
Description: "Manage fleet vehicles including registration and maintenance"
```

### Transport Officer
```
[Add Vehicle Button] - HIDDEN
[Edit Icon] - Visible (limited fields)
[Delete Icon] - HIDDEN
Description: "View available vehicles and check availability for bookings"
```

### Driver
```
[Add Vehicle Button] - HIDDEN
[Edit Icon] - HIDDEN
[Delete Icon] - HIDDEN
Description: "View your assigned vehicles and their details"
Filter: Only show assigned vehicles
```

---

## 🔒 Security Notes

### Frontend (UI Control)
- Hide buttons/actions based on permissions
- Disable form fields based on role
- Filter data display based on role
- **Note**: UI control only, backend enforces actual security

### Backend (Already Implemented)
- ✅ Permission middleware on all endpoints
- ✅ VehicleController checks permissions
- ✅ API returns 403 for unauthorized actions
- ✅ Database-level access control

---

## 📝 Files to Modify

### Frontend
1. `src/hooks/usePermissions.ts` - NEW (permission hook)
2. `src/pages/VehiclesPageV2.tsx` - UPDATE (add permission checks)
3. `src/components/vehicles/VehicleFormModal.tsx` - UPDATE (field restrictions)
4. `src/components/vehicles/VehicleDetailsModal.tsx` - UPDATE (hide actions)
5. `src/components/vehicles/VehicleStats.tsx` - UPDATE (role-based stats)

### Backend
- ✅ Already implemented (no changes needed)
- VehicleController has permission checks
- API endpoints protected

---

## ✅ Success Criteria

- [ ] Super Admin sees full access
- [ ] Admin sees full access
- [ ] Fleet Manager cannot delete vehicles
- [ ] Transport Officer sees limited edit (status/notes only)
- [ ] Driver sees only assigned vehicles (read-only)
- [ ] Add button hidden for Transport Officer & Driver
- [ ] Delete button hidden for Fleet Manager, Transport Officer & Driver
- [ ] Edit button shows appropriate fields based on role
- [ ] Page description changes based on role
- [ ] Statistics adapt to role context

---

## 🚀 Next Steps

1. Create `usePermissions` hook
2. Update VehiclesPageV2 with permission checks
3. Update vehicle modals with role-based restrictions
4. Test with all 5 roles
5. Document changes

---

**Status**: AUDIT COMPLETE  
**Next**: Implement permission-based UI controls  
**Priority**: HIGH (Security & UX)
