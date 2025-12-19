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
            'view_policy_compliance',
            'monitor_budget_execution',
            'audit_user_accounts',
            'intervene_in_workflows',
            'access_strategic_dashboards',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles and assign permissions
        $admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $cabinetSecretary = Role::create(['name' => 'cabinet_secretary', 'guard_name' => 'web']);
        $cabinetSecretary->givePermissionTo([
            'view dashboard',
            'view reports',
            'view_policy_compliance',
            'monitor_budget_execution',
            'audit_user_accounts',
            'intervene_in_workflows',
            'access_strategic_dashboards'
        ]);

        $manager = Role::create(['name' => 'fleet_manager', 'guard_name' => 'web']);
        $manager->givePermissionTo(['view dashboard', 'manage fleet', 'view reports']);

        $driver = Role::create(['name' => 'authorized_driver', 'guard_name' => 'web']);
        $driver->givePermissionTo(['view dashboard']);
    }
}
