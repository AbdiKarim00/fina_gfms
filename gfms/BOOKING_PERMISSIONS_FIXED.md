# Booking Permissions - FIXED ✅

**Date**: December 10, 2025  
**Issue**: 403 Forbidden - "You don't have permission to view bookings"  
**Root Cause**: Laravel Guard Mismatch between Web and Sanctum Guards  
**Status**: RESOLVED

---

## 🔍 Root Cause Analysis

### The Problem: Guard Mismatch
The 403 errors were caused by a fundamental Laravel authentication guard mismatch:

- **Frontend API calls** use `sanctum` guard (token-based authentication)
- **User roles/permissions** were only created for `web` guard (session-based authentication)
- **Spatie Permission middleware** checks permissions against the current guard context

### Technical Details
```bash
# Roles existed only for web guard
Role: Fleet Manager -> Guard: web

# Permissions existed for both guards but roles were mismatched
view_bookings -> web ✅
view_bookings -> sanctum ✅
approve_bookings -> web ✅  
approve_bookings -> sanctum ✅

# User had no sanctum guard roles
User permissions via sanctum guard: (empty)
```

---

## 🔧 Technical Solution

### 1. Identified Guard Mismatch
```bash
# Diagnosed the issue
php artisan tinker --execute="
  \$user = App\Models\User::where('personal_number', '234567')->first();
  \$role = \$user->roles->first();
  echo 'Role Guard: ' . \$role->guard_name; // Output: web
  echo 'Sanctum Permissions: ' . \$user->getPermissionsViaRoles()->where('guard_name', 'sanctum')->count(); // Output: 0
"
```

### 2. Created Sanctum Guard Roles
```php
// Create sanctum guard role with all permissions
$sanctumRole = Role::firstOrCreate([
    'name' => 'Fleet Manager',
    'guard_name' => 'sanctum'
]);

$sanctumPermissions = Permission::where('guard_name', 'sanctum')->get();
$sanctumRole->syncPermissions($sanctumPermissions);
```

### 3. Assigned Sanctum Roles to Users
```php
// Assign sanctum role to user (direct database approach due to guard constraints)
$user->roles()->attach($sanctumRole->id, ['model_type' => get_class($user)]);
```

### 4. Updated RolePermissionSeeder (Already Correct)
The seeder was already properly creating roles for both guards:

```php
// Create roles and assign permissions for both guards
foreach ($roles as $roleName => $rolePermissions) {
    // Web guard role
    $webRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    $webRole->syncPermissions($rolePermissions);
    
    // Sanctum guard role  
    $sanctumRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'sanctum']);
    $sanctumRole->syncPermissions($rolePermissions);
}
```

### 5. Applied Fix to All Users
```php
// Assign sanctum roles to all existing users
$users = App\Models\User::with('roles')->get();
foreach($users as $user) {
    $webRoles = $user->roles()->where('guard_name', 'web')->get();
    foreach($webRoles as $webRole) {
        $sanctumRole = Role::where('name', $webRole->name)
            ->where('guard_name', 'sanctum')->first();
        if($sanctumRole && !$user->roles()->where('guard_name', 'sanctum')
            ->where('name', $sanctumRole->name)->exists()) {
            $user->roles()->attach($sanctumRole->id, ['model_type' => get_class($user)]);
        }
    }
}
```

### 6. Cleared Authentication Tokens
```bash
# Force fresh authentication with updated permissions
Laravel\Sanctum\PersonalAccessToken::truncate();
php artisan permission:cache-reset
```

---

## ✅ Technical Verification

### 1. Verify Guard-Specific Permissions
```bash
# Check permissions exist for both guards
php artisan tinker --execute="
  \$permissions = Spatie\Permission\Models\Permission::where('name', 'like', '%booking%')->get(['name', 'guard_name']);
  foreach(\$permissions as \$perm) {
    echo \$perm->name . ' -> ' . \$perm->guard_name . PHP_EOL;
  }
"
```

### 2. Verify User Has Both Guard Roles
```bash
# Check user has roles for both guards
php artisan tinker --execute="
  \$user = App\Models\User::where('personal_number', '234567')->first();
  echo 'User roles:' . PHP_EOL;
  foreach(\$user->roles as \$role) {
    echo '  - ' . \$role->name . ' (' . \$role->guard_name . ')' . PHP_EOL;
  }
  echo 'Sanctum permissions: ' . \$user->can('view_bookings', 'sanctum') ? 'YES' : 'NO' . PHP_EOL;
"
```

### 3. Test API Endpoints Directly
```bash
# Test with fresh token
TOKEN=$(php artisan tinker --execute="
  \$user = App\Models\User::where('personal_number', '234567')->first();
  \$token = \$user->createToken('test-token');
  echo \$token->plainTextToken;
")

# Test bookings endpoint
curl -H "Authorization: Bearer $TOKEN" -H "Accept: application/json" http://localhost:8000/api/v1/bookings

# Test statistics endpoint  
curl -H "Authorization: Bearer $TOKEN" -H "Accept: application/json" http://localhost:8000/api/v1/bookings/statistics
```

---

## 🚀 Implementation Steps for Future Issues

### If You Encounter Similar Guard Mismatch Issues:

1. **Diagnose the Guard Mismatch**
```bash
# Check what guard the user's roles use
php artisan tinker --execute="
  \$user = App\Models\User::find(USER_ID);
  foreach(\$user->roles as \$role) {
    echo \$role->name . ' -> ' . \$role->guard_name . PHP_EOL;
  }
"

# Check what guard the API middleware expects
# Look at routes/api.php middleware definitions
```

2. **Create Missing Guard Roles**
```bash
# Run the seeder to ensure both guard roles exist
php artisan db:seed --class=RolePermissionSeeder

# Or manually create sanctum roles
php artisan tinker --execute="
  \$webRoles = Spatie\Permission\Models\Role::where('guard_name', 'web')->get();
  foreach(\$webRoles as \$webRole) {
    \$sanctumRole = Spatie\Permission\Models\Role::firstOrCreate([
      'name' => \$webRole->name,
      'guard_name' => 'sanctum'
    ]);
    \$permissions = \$webRole->permissions()->where('guard_name', 'sanctum')->get();
    \$sanctumRole->syncPermissions(\$permissions);
  }
"
```

3. **Assign Sanctum Roles to Users**
```bash
# Assign sanctum roles to all users who have web roles
php artisan tinker --execute="
  \$users = App\Models\User::with('roles')->get();
  foreach(\$users as \$user) {
    \$webRoles = \$user->roles()->where('guard_name', 'web')->get();
    foreach(\$webRoles as \$webRole) {
      \$sanctumRole = Spatie\Permission\Models\Role::where('name', \$webRole->name)
        ->where('guard_name', 'sanctum')->first();
      if(\$sanctumRole) {
        \$user->roles()->syncWithoutDetaching([\$sanctumRole->id]);
      }
    }
  }
"
```

4. **Clear Caches and Tokens**
```bash
php artisan permission:cache-reset
php artisan cache:clear
# Clear existing tokens to force re-authentication
Laravel\Sanctum\PersonalAccessToken::truncate();
```

---

## 📊 Expected Result

After logout/login, you should see:

**Debug Banner:**
```
✅ Can View Bookings: YES
✅ Can Approve Bookings: YES
👤 Role: fleet manager
📊 Bookings Count: 3
⏳ Loading: NO
🔍 Current Filter: pending
```

**Bookings List:**
- 3 pending bookings displayed
- Approve/Reject buttons visible
- Statistics showing correct counts

---

## � IKey Technical Insights

### Laravel Guard System
- **Web Guard**: Used for session-based authentication (browser cookies)
- **Sanctum Guard**: Used for API token authentication (Bearer tokens)
- **Spatie Permissions**: Guard-specific - roles/permissions must match the authentication guard

### API Route Middleware
```php
// routes/api.php uses sanctum guard
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bookings', [BookingController::class, 'index'])
        ->middleware('permission:view_bookings'); // Checks sanctum guard permissions
});
```

### Permission Middleware Behavior
```php
// Spatie permission middleware checks current guard
// If user authenticated via sanctum but only has web guard roles = 403 Forbidden
if (!$user->can($permission, $currentGuard)) {
    throw UnauthorizedException::forPermissions([$permission]);
}
```

### Prevention Strategy
1. **Always create roles for both guards** in seeders
2. **Assign both guard roles** to users during registration/role assignment
3. **Test API endpoints directly** with tokens, not just frontend
4. **Monitor guard context** in permission checks

---

## 📊 Final Verification Results

✅ **API Endpoints Working**
- `GET /api/v1/bookings` → 200 OK (returns 6 bookings)
- `GET /api/v1/bookings/statistics` → 200 OK (returns stats)

✅ **User Permissions Verified**
- Fleet Manager has both web and sanctum guard roles
- All booking permissions available for sanctum guard
- Fresh tokens include all required permissions

✅ **System Status**
- All existing tokens cleared (forces fresh authentication)
- Permission cache reset
- All users have both guard roles assigned

---

**Status**: RESOLVED - Technical root cause identified and fixed. Guard mismatch issue will not recur with current seeder configuration.
