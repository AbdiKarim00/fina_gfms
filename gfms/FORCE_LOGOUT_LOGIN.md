# FORCE LOGOUT AND LOGIN - CRITICAL!

**The bookings page will NOT work until you do this!**

---

## Why You Must Logout/Login

Your current auth token was created **BEFORE** the booking permissions existed in the database.

**Sanctum tokens are stateless** - they contain a snapshot of permissions at the time of creation. Even though we added permissions to the database, your existing token doesn't have them.

---

## Step-by-Step Instructions

### 1. Clear Browser Storage (IMPORTANT!)
Open browser console (F12) and run:
```javascript
localStorage.clear();
sessionStorage.clear();
location.reload();
```

OR manually:
1. Open DevTools (F12)
2. Go to "Application" tab
3. Click "Local Storage" → "http://localhost:3000"
4. Right-click → "Clear"
5. Refresh page

### 2. Login Fresh
1. You'll be redirected to login page
2. Enter credentials:
   - Personal Number: `234567`
   - Password: `password`
3. Get OTP from Mailhog: http://localhost:8025
4. Enter OTP
5. Login

### 3. Verify New Token Has Permissions
Open console and check:
```javascript
// Check what's in localStorage
console.log('Token:', localStorage.getItem('auth_token'));

// The token should be fresh (just created)
```

### 4. Navigate to Bookings
Click "Bookings" in sidebar

---

## What Changed

### Before (Old Behavior)
- Default filter: "pending"
- Used `/bookings/pending` endpoint
- Required `approve_bookings` permission
- ❌ Your old token didn't have this permission

### After (New Behavior)
- Default filter: "all"
- Uses `/bookings` endpoint with status filter
- Only requires `view_bookings` permission
- ✅ Works with basic view permission

---

## Expected Result After Fresh Login

**Debug Banner Should Show:**
```
✅ Can View Bookings: YES
✅ Can Approve Bookings: YES
👤 Role: fleet manager
📊 Bookings Count: 6
⏳ Loading: NO
🔍 Current Filter: (empty/all)
```

**Console Should Show:**
```
🚀 BookingsPage component mounted
🔐 User permissions: { canViewBookings: true, canApproveBookings: true, ... }
🌐 API Request: GET /bookings
✅ API Response: { status: 200, data: { success: true, data: [6 bookings] } }
📋 Bookings array: [6 items]
```

**Page Should Show:**
- 6 total bookings
- Statistics cards with correct counts
- Approve/Reject buttons on pending bookings
- No errors!

---

## If Still Not Working

### Check Token in Database
```bash
docker exec gfms_app php artisan tinker --execute="
\$user = App\Models\User::where('personal_number', '234567')->first();
\$token = \$user->tokens()->latest()->first();
echo 'Latest token created: ' . \$token->created_at . PHP_EOL;
echo 'Token abilities: ' . json_encode(\$token->abilities) . PHP_EOL;
"
```

### Revoke All Old Tokens
```bash
docker exec gfms_app php artisan tinker --execute="
\$user = App\Models\User::where('personal_number', '234567')->first();
\$user->tokens()->delete();
echo 'All tokens revoked. Please login again.' . PHP_EOL;
"
```

Then login fresh!

---

**DO THIS NOW:** Clear localStorage and login fresh!
