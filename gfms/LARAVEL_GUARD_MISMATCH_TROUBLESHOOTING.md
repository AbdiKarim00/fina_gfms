# Laravel Guard Mismatch Troubleshooting Guide

**Created**: December 10, 2025  
**Context**: Spatie Laravel Permission with Multiple Guards  
**Issue Type**: 403 Forbidden errors on API endpoints despite user having permissions

---

## 🔍 Problem Identification

### Symptoms
- ✅ User can login successfully
- ✅ User has roles and permissions in database
- ❌ API returns 403 "You don't have permission to perform this action"
- ❌ Frontend shows "You don't have permission to view [resource]"

### Quick Diagnostic Commands

```bash
# 1. Check user's current roles and their guards
php artisan tinker --execute="
  \$user = App\Models\User::find(USER_ID);
  echo 'User: ' . \$user->name . PHP_EOL;
  foreach(\$user->roles as \$role) {
    echo 'Role: ' . \$role->name . ' (Guard: ' . \$role->guard_name . ')' . PHP_EOL;
  }
"

# 2. Check what guards permissions exist for
php artisan tinker --execute="
  \$permissions = Spatie\Permission\Models\Permission::where('name', 'PERMISSION_NAME')->get(['name', 'guard_name']);
  foreach(\$permissions as \$perm) {
    echo \$perm->name . ' -> ' . \$perm->guard_name . PHP_EOL;
  }
"

# 3. Test permission check for specific guard
php artisan tinker --execute="
  \$user = App\Models\User::find(USER_ID);
  echo 'Can PERMISSION_NAME (web): ' . (\$user->can('PERMISSION_NAME', 'web') ? 'YES' : 'NO') . PHP_EOL;
  echo 'Can PERMISSION_NAME (sanctum): ' . (\$user->can('PERMISSION_NAME', 'sanctum') ? 'YES' : 'NO') . PHP_EOL;
"
```

---

## 🔧 Root Cause Analysis

### Laravel Authentication Guards
Laravel supports multiple authentication guards simultaneously:

- **Web Guard** (`auth:web`): Session-based authentication using cookies
- **Sanctum Guard** (`auth:sanctum`): Token-based authentication using Bearer tokens
- **API Guard** (`auth:api`): Traditional API token authentication

### Spatie Permission Guard Binding
Spatie Laravel Permission creates **guard-specific** roles and permissions:

```php
// These are DIFFERENT entities in the database
Role::create(['name' => 'Admin', 'guard_name' => 'web']);     // For web sessions
Role::create(['name' => 'Admin', 'guard_name' => 'sanctum']); // For API tokens
```

### The Mismatch Problem
```php
// User logs in via web (session)
$user->assignRole('Admin'); // Assigns web guard role by default

// API call uses sanctum guard
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/resource', [Controller::class, 'index'])
        ->middleware('permission:view_resource'); // Checks sanctum guard permissions
});

// Result: 403 Forbidden because user has web guard role but API needs sanctum guard permissions
```

---

## 🛠️ Technical Solutions

### Solution 1: Ensure Seeder Creates Both Guard Roles

```php
// database/seeders/RolePermissionSeeder.php
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = ['view_resource', 'create_resource', 'edit_resource'];
        
        // Create permissions for both guards
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'sanctum']);
        }
        
        $roles = [
            'Admin' => $permissions,
            'User' => ['view_resource'],
        ];
        
        // Create roles for both guards
        foreach ($roles as $roleName => $rolePermissions) {
            // Web guard role
            $webRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $webRole->syncPermissions($rolePermissions);
            
            // Sanctum guard role
            $sanctumRole = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'sanctum']);
            $sanctumRole->syncPermissions($rolePermissions);
        }
    }
}
```

### Solution 2: Assign Both Guard Roles to Users

```php
// When creating/updating users
class UserService
{
    public function assignRole(User $user, string $roleName): void
    {
        // Assign web guard role (for session-based access)
        $user->assignRole($roleName, 'web');
        
        // Assign sanctum guard role (for API access)
        $sanctumRole = Role::where('name', $roleName)
            ->where('guard_name', 'sanctum')
            ->first();
            
        if ($sanctumRole) {
            $user->roles()->syncWithoutDetaching([$sanctumRole->id]);
        }
    }
}
```

### Solution 3: Fix Existing Users (Migration Script)

```php
// Create artisan command or run in tinker
class FixGuardMismatch
{
    public function handle(): void
    {
        $users = User::with('roles')->get();
        
        foreach ($users as $user) {
            // Get user's web guard roles
            $webRoles = $user->roles()->where('guard_name', 'web')->get();
            
            foreach ($webRoles as $webRole) {
                // Find corresponding sanctum role
                $sanctumRole = Role::where('name', $webRole->name)
                    ->where('guard_name', 'sanctum')
                    ->first();
                
                if ($sanctumRole) {
                    // Check if user already has this sanctum role
                    $hasRole = $user->roles()
                        ->where('guard_name', 'sanctum')
                        ->where('name', $sanctumRole->name)
                        ->exists();
                    
                    if (!$hasRole) {
                        $user->roles()->attach($sanctumRole->id, [
                            'model_type' => get_class($user)
                        ]);
                    }
                }
            }
        }
        
        // Clear permission cache
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
```

---

## 🧪 Testing and Verification

### 1. Verify Role Creation
```bash
php artisan tinker --execute="
  echo 'Roles by guard:' . PHP_EOL;
  \$roles = Spatie\Permission\Models\Role::all(['name', 'guard_name']);
  foreach(\$roles as \$role) {
    echo '  ' . \$role->name . ' -> ' . \$role->guard_name . PHP_EOL;
  }
"
```

### 2. Test API Endpoint Directly
```bash
# Get a fresh token
TOKEN=$(php artisan tinker --execute="
  \$user = App\Models\User::first();
  echo \$user->createToken('test')->plainTextToken;
")

# Test API endpoint
curl -H "Authorization: Bearer $TOKEN" \
     -H "Accept: application/json" \
     http://localhost:8000/api/v1/resource
```

### 3. Verify User Permissions
```bash
php artisan tinker --execute="
  \$user = App\Models\User::first();
  echo 'User: ' . \$user->name . PHP_EOL;
  echo 'Web permissions: ' . \$user->getPermissionsViaRoles()->where('guard_name', 'web')->count() . PHP_EOL;
  echo 'Sanctum permissions: ' . \$user->getPermissionsViaRoles()->where('guard_name', 'sanctum')->count() . PHP_EOL;
"
```

---

## 🚨 Prevention Strategies

### 1. Always Use Both Guards in Seeders
```php
// GOOD: Creates roles for both guards
foreach ($roles as $roleName => $permissions) {
    Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'sanctum']);
}

// BAD: Only creates web guard roles
foreach ($roles as $roleName => $permissions) {
    Role::firstOrCreate(['name' => $roleName]); // Defaults to web guard
}
```

### 2. Create Helper Methods
```php
// app/Traits/HasBothGuardRoles.php
trait HasBothGuardRoles
{
    public function assignRoleBothGuards(string $roleName): void
    {
        $this->assignRole($roleName, 'web');
        
        $sanctumRole = Role::where('name', $roleName)
            ->where('guard_name', 'sanctum')
            ->first();
            
        if ($sanctumRole) {
            $this->roles()->syncWithoutDetaching([$sanctumRole->id]);
        }
    }
    
    public function canBothGuards(string $permission): bool
    {
        return $this->can($permission, 'web') && $this->can($permission, 'sanctum');
    }
}
```

### 3. Add Tests
```php
// tests/Feature/PermissionGuardTest.php
class PermissionGuardTest extends TestCase
{
    public function test_user_has_permissions_for_both_guards(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        
        // Should work for both guards
        $this->assertTrue($user->can('view_resource', 'web'));
        $this->assertTrue($user->can('view_resource', 'sanctum'));
    }
    
    public function test_api_endpoint_works_with_token(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Admin');
        $token = $user->createToken('test')->plainTextToken;
        
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->get('/api/v1/resource');
        
        $response->assertStatus(200);
    }
}
```

---

## 📋 Checklist for New Projects

- [ ] Seeder creates permissions for both `web` and `sanctum` guards
- [ ] Seeder creates roles for both `web` and `sanctum` guards  
- [ ] User registration assigns roles for both guards
- [ ] API routes use `auth:sanctum` middleware
- [ ] Web routes use `auth:web` middleware (or default `auth`)
- [ ] Tests verify permissions work for both guards
- [ ] Documentation explains guard system to team

---

## 🔗 Related Laravel Documentation

- [Laravel Authentication Guards](https://laravel.com/docs/authentication#guards)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Spatie Laravel Permission](https://spatie.be/docs/laravel-permission/v6/basic-usage/multiple-guards)

---

**Remember**: Guard mismatch is a common issue when mixing session-based and token-based authentication. Always ensure your roles and permissions exist for the guard context your application uses.