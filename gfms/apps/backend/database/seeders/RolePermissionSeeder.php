<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Truncate tables using raw SQL to handle schema
        DB::statement('TRUNCATE TABLE permissions.role_has_permissions CASCADE');
        DB::statement('TRUNCATE TABLE permissions.model_has_roles CASCADE');
        DB::statement('TRUNCATE TABLE permissions.model_has_permissions CASCADE');
        DB::statement('TRUNCATE TABLE permissions.roles CASCADE');
        DB::statement('TRUNCATE TABLE permissions.permissions CASCADE');

        // Create Permissions
        $permissions = [
            'view dashboard',
            'manage users',
            'manage fleet',
            'view reports',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles and assign permissions
        $admin = Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::create(['name' => 'Manager', 'guard_name' => 'web']);
        $manager->givePermissionTo(['view dashboard', 'manage fleet', 'view reports']);

        $driver = Role::create(['name' => 'Driver', 'guard_name' => 'web']);
        $driver->givePermissionTo(['view dashboard']);
    }
}
