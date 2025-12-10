# Debugging Guide - GFMS Frontend

**Date**: December 9, 2025  
**Purpose**: Comprehensive debugging for development

---

## 🐛 Debugging Features Added

### 1. **BookingsPage Debug Banner**
When in DEV mode, you'll see a yellow debug banner at the top showing:
- ✅ Can View Bookings permission
- ✅ Can Approve Bookings permission
- 👤 Current user role
- 📊 Number of bookings loaded
- ⏳ Loading state
- 🔍 Current filter applied

### 2. **Console Logging**

#### Component Lifecycle
```
🚀 BookingsPage component mounted
```

#### Permissions
```
🔐 User permissions: { canViewBookings: true, canApproveBookings: true, ... }
👁️ Can view bookings: true
✅ Can approve bookings: true
```

#### API Requests
```
🌐 API Request: {
  method: 'GET',
  url: '/bookings/pending',
  fullURL: 'http://localhost:8000/api/v1/bookings/pending',
  headers: { Authorization: 'Bearer ...' }
}
```

#### API Responses
```
✅ API Response: {
  status: 200,
  statusText: 'OK',
  url: '/bookings/pending',
  data: { success: true, data: [...] }
}
```

#### Errors
```
❌ API Error: {
  message: 'Request failed with status code 500',
  status: 500,
  statusText: 'Internal Server Error',
  url: '/bookings/pending',
  data: { message: 'Error details...' }
}
```

---

## 📋 How to Use

### Step 1: Open Browser Console
1. Open http://localhost:3000
2. Press `F12` or `Cmd+Option+I` (Mac) / `Ctrl+Shift+I` (Windows)
3. Go to the "Console" tab

### Step 2: Login
1. Login with Fleet Manager: `234567` / `password`
2. Check OTP in Mailhog: http://localhost:8025
3. Enter OTP and login

### Step 3: Navigate to Bookings
1. Click "Bookings" in the sidebar
2. Watch the console for detailed logs

### Step 4: Analyze Logs
Look for these key indicators:

**✅ Success Pattern:**
```
🚀 BookingsPage component mounted
🔐 User permissions: {...}
🌐 API Request: GET /bookings/pending
✅ API Response: { status: 200, data: [...] }
🔍 Fetching bookings from: /bookings/pending
✅ Bookings API Response: {...}
📦 Response data: [...]
📋 Bookings array: [6 items]
```

**❌ Error Pattern:**
```
🚀 BookingsPage component mounted
🔐 User permissions: {...}
🌐 API Request: GET /bookings/pending
❌ API Error: { status: 500, message: '...' }
❌ ERROR fetching bookings: {...}
📍 Error details: {...}
```

---

## 🔍 Common Issues & Solutions

### Issue 1: "Failed to fetch bookings"
**Console shows:**
```
❌ API Error: { status: 500 }
```

**Solution:**
1. Check backend logs: `docker logs gfms_app --tail 50`
2. Check if bookings table exists: `docker exec gfms_app php artisan migrate:status`
3. Check if data is seeded: Run seeder again

### Issue 2: "Unauthenticated"
**Console shows:**
```
❌ API Error: { status: 401, message: 'Unauthenticated.' }
🔒 Unauthorized - redirecting to login
```

**Solution:**
1. Token expired - login again
2. Check localStorage has `auth_token`
3. Check token is being sent in request headers

### Issue 3: "You don't have permission to view bookings"
**Console shows:**
```
⚠️ User does not have permission to view bookings
🔐 User permissions: { canViewBookings: false }
```

**Solution:**
1. Check user role has `view_bookings` permission
2. Run: `docker exec gfms_app php artisan db:seed --class=RolePermissionSeeder`
3. Logout and login again

### Issue 4: Empty bookings array
**Console shows:**
```
✅ Bookings API Response: { success: true, data: [] }
📋 Bookings array: []
```

**Solution:**
1. No bookings in database
2. Run: `docker exec gfms_app php artisan db:seed --class=BookingSeeder`
3. Refresh the page

### Issue 5: Wrong data structure
**Console shows:**
```
📦 Response data: { items: [...], meta: {...} }
```

**Solution:**
1. API is returning paginated data
2. Update frontend to handle `response.data.data` instead of `response.data`

---

## 🛠️ Debug Commands

### Check Backend Status
```bash
# Check if backend is running
docker ps | grep gfms_app

# Check backend logs
docker logs gfms_app --tail 50

# Check Laravel logs
docker exec gfms_app tail -50 storage/logs/laravel.log
```

### Check Database
```bash
# Check migrations
docker exec gfms_app php artisan migrate:status

# Check bookings count
docker exec gfms_app php artisan tinker --execute="echo App\Models\Booking::count();"

# Check permissions count
docker exec gfms_app php artisan tinker --execute="echo App\Models\Permission::count();"
```

### Test API Directly
```bash
# Get token (after login + OTP)
TOKEN="your_token_here"

# Test bookings endpoint
curl -H "Authorization: Bearer $TOKEN" \
     -H "Accept: application/json" \
     http://localhost:8000/api/v1/bookings/pending

# Test statistics endpoint
curl -H "Authorization: Bearer $TOKEN" \
     -H "Accept: application/json" \
     http://localhost:8000/api/v1/bookings/statistics
```

---

## 📊 Expected Console Output (Success)

When everything works correctly, you should see:

```
🚀 BookingsPage component mounted
🔐 User permissions: {
  canViewBookings: true,
  canCreateBookings: true,
  canEditBookings: true,
  canDeleteBookings: false,
  canApproveBookings: true,
  canCancelBookings: true,
  role: "fleet manager"
}
👁️ Can view bookings: true
✅ Can approve bookings: true

🌐 API Request: {
  method: "GET",
  url: "/bookings/pending",
  fullURL: "http://localhost:8000/api/v1/bookings/pending",
  headers: { Authorization: "Bearer eyJ0eXAiOiJKV1QiLCJhbGc..." }
}

✅ API Response: {
  status: 200,
  statusText: "OK",
  data: {
    success: true,
    data: [
      { id: 1, vehicle_id: 1, status: "pending", ... },
      { id: 2, vehicle_id: 2, status: "pending", ... },
      { id: 3, vehicle_id: 3, status: "pending", ... }
    ],
    meta: { current_page: 1, total: 3 }
  }
}

🔍 Fetching bookings from: /bookings/pending
✅ Bookings API Response: { success: true, data: [...] }
📦 Response data: [Array(3)]
📋 Bookings array: [3 items]

📊 Fetching booking statistics...
✅ Statistics API Response: { success: true, data: {...} }
📈 Statistics data: { total: 6, pending: 3, approved: 1, rejected: 1 }
```

---

## 🎯 Next Steps

1. **Open browser console** (F12)
2. **Navigate to Bookings page**
3. **Copy all console output**
4. **Share the output** to diagnose the issue

The detailed logs will show exactly where the problem is!
