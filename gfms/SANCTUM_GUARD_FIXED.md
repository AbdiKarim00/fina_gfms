# Sanctum Guard Issue - FIXED ✅

**Date**: December 9, 2025  
**Root Cause**: Permissions created for `web` guard but routes checking `sanctum` guard  
**Status**: RESOLVED

---

## 🔍 The Problem

**Error**: "There is no permission named `view_bookings` for guard `sanctum`"

**Root Cause**:
- Permissions were created with `guard_name => 'web'`
- Roles were created with `guard_name => 'web'`
- But Sanctum API authentication uses `guard_name => 'sanctum'`
- The middleware was checking for permissions in the wrong guard

---

## ✅ The Solution

### 1. Updated RolePermissionSeeder
Created permissions and roles for BOTH guards:

```php
// Create permissions for both guards
foreach ($permissions as $permission) {
    // Web guard (for session-based auth)
    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    
    // Sanctum guard (for API token auth)
    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
}

// Create roles for both guards
foreach ($roles as $roleName => $rolePermissions) {
    // Web guard role
    $webRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    $webRole->syncPermissions($rolePermissions);
    
    // Sanctum guard role
    $sanctumRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'sanctum']);
    $sanctumRole->syncPermissions($rolePermissions);
}
```

### 2. Assigned Sanctum Roles to Existing Users
```php
$users = App\Models\User::with('roles')->get();
foreach ($users as $user) {
    $webRole = $user->roles->first();
    if ($webRole) {
        $sanctumRole = Role::where('name', $webRole->name)
            ->where('guard_name', 'sanctum')
            ->first();
        $user->assignRole($sanctumRole);
    }
}
```

### 3. Cleared All Caches and Tokens
- Revoked all existing tokens
- Cleared permission cache
- Cleared application cache
- Restarted backend

---

## 📊 Verification

### Permissions Created
```bash
Web guard: 51 permissions
Sanctum guard: 51 permissions

Booking permissions (sanctum):
  - view_bookings
  - create_bookings
  - edit_bookings
  - delete_bookings
  - approve_bookings
  - cancel_bookings
```

### Roles Created
```bash
Web guard: 8 roles
Sanctum guard: 8 roles

Sanctum roles:
  - Super Admin
  - Admin
  - Fleet Manager
  - Transport Officer
  - Finance Officer
  - Driver
  - CMTE Inspector
  - Viewer
```

### User Verification (Fleet Manager)
```bash
User: Jane Fleet Manager
Roles:
  - Fleet Manager (web)
  - Fleet Manager (sanctum)

Can view_bookings (sanctum): YES
Can approve_bookings (sanctum): YES
```

---

## 🚀 What You Need to Do NOW

### Step 1: Clear Browser Storage
In browser console (F12):
```javascript
localStorage.clear();
sessionStorage.clear();
location.reload();
```

### Step 2: Login Fresh
1. Login with `234567` / `password`
2. Get OTP from http://localhost:8025
3. Complete login

### Step 3: Test Bookings Page
Navigate to Bookings - should work now!

---

## 🎯 Expected Result

**Debug Banner:**
```
✅ Can View Bookings: YES
✅ Can Approve Bookings: YES
👤 Role: fleet manager
📊 Bookings Count: 6
⏳ Loading: NO
🔍 Current Filter: (empty)
```

**Console:**
```
🚀 BookingsPage component mounted
🔐 User permissions: { canViewBookings: true, canApproveBookings: true, ... }
🌐 API Request: GET /bookings
✅ API Response: { status: 200, data: [...] }
📋 Bookings array: [6 items]
```

**Page:**
- 6 bookings displayed
- Statistics showing correct counts
- Approve/Reject buttons visible
- NO ERRORS!

---

## 📝 What Changed

### Backend
- ✅ 51 permissions created for `sanctum` guard
- ✅ 8 roles created for `sanctum` guard
- ✅ All users assigned sanctum roles
- ✅ All tokens revoked (must login fresh)
- ✅ Caches cleared
- ✅ Backend restarted

### Frontend
- ✅ Default filter changed to "all" (not "pending")
- ✅ Uses `/bookings` endpoint (not `/bookings/pending`)
- ✅ Better error handling
- ✅ Helpful error messages

---

## 🔧 For Future Reference

When adding new permissions:
1. Create for BOTH `web` and `sanctum` guards
2. Assign to roles for BOTH guards
3. Clear permission cache
4. Users must logout/login to get new permissions

---

**Status**: FIXED! Clear localStorage and login fresh to test.
