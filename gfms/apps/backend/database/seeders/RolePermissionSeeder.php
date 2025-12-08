<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all permissions grouped by resource
        $permissions = [
            // Vehicle Management
            'view_vehicles',
            'create_vehicles',
            'edit_vehicles',
            'delete_vehicles',
            'assign_vehicles',
            
            // Driver Management
            'view_drivers',
            'create_drivers',
            'edit_drivers',
            'delete_drivers',
            'assign_drivers',
            
            // Trip Management
            'view_trips',
            'create_trips',
            'edit_trips',
            'delete_trips',
            'approve_trips',
            
            // Maintenance Management
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            'delete_maintenance',
            'approve_maintenance',
            
            // Fuel Management
            'view_fuel',
            'create_fuel',
            'edit_fuel',
            'delete_fuel',
            'approve_fuel',
            
            // Inspection Management
            'view_inspections',
            'create_inspections',
            'edit_inspections',
            'delete_inspections',
            'approve_inspections',
            
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            'assign_roles',
            
            // Organization Management
            'view_organizations',
            'create_organizations',
            'edit_organizations',
            'delete_organizations',
            
            // Report Management
            'view_reports',
            'export_reports',
            'view_analytics',
            
            // System Settings
            'manage_settings',
            'view_audit_logs',
            'manage_system',
        ];

        // Create all permissions
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'web']);
        }

        // Define roles and their permissions
        $roles = [
            'Super Admin' => Permission::all()->pluck('name')->toArray(),
            
            'Admin' => [
                'view_vehicles', 'create_vehicles', 'edit_vehicles', 'delete_vehicles', 'assign_vehicles',
                'view_drivers', 'create_drivers', 'edit_drivers', 'delete_drivers', 'assign_drivers',
                'view_trips', 'create_trips', 'edit_trips', 'delete_trips', 'approve_trips',
                'view_maintenance', 'create_maintenance', 'edit_maintenance', 'delete_maintenance', 'approve_maintenance',
                'view_fuel', 'create_fuel', 'edit_fuel', 'delete_fuel', 'approve_fuel',
                'view_inspections', 'create_inspections', 'edit_inspections', 'delete_inspections',
                'view_users', 'create_users', 'edit_users', 'assign_roles',
                'view_organizations', 'edit_organizations',
                'view_reports', 'export_reports', 'view_analytics',
                'view_audit_logs',
            ],
            
            'Fleet Manager' => [
                'view_vehicles', 'create_vehicles', 'edit_vehicles', 'assign_vehicles',
                'view_drivers', 'create_drivers', 'edit_drivers', 'assign_drivers',
                'view_trips', 'create_trips', 'edit_trips', 'approve_trips',
                'view_maintenance', 'create_maintenance', 'edit_maintenance', 'approve_maintenance',
                'view_fuel', 'create_fuel', 'edit_fuel', 'approve_fuel',
                'view_inspections', 'create_inspections', 'edit_inspections',
                'view_users',
                'view_reports', 'export_reports', 'view_analytics',
            ],
            
            'Transport Officer' => [
                'view_vehicles', 'edit_vehicles',
                'view_drivers', 'edit_drivers',
                'view_trips', 'create_trips', 'edit_trips',
                'view_maintenance', 'create_maintenance',
                'view_fuel', 'create_fuel',
                'view_inspections',
                'view_reports',
            ],
            
            'Finance Officer' => [
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance', 'approve_maintenance',
                'view_fuel', 'approve_fuel',
                'view_reports', 'export_reports', 'view_analytics',
            ],
            
            'Driver' => [
                'view_vehicles',
                'view_trips',
                'view_maintenance',
                'view_fuel', 'create_fuel',
                'view_inspections',
            ],
            
            'CMTE Inspector' => [
                'view_vehicles',
                'view_drivers',
                'view_inspections', 'create_inspections', 'edit_inspections', 'approve_inspections',
                'view_reports',
            ],
            
            'Viewer' => [
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_reports',
            ],
        ];

        // Create roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'web']);
            $role->givePermissionTo($rolePermissions);
        }

        $this->command->info('✓ Roles and permissions created successfully!');
        $this->command->info('  - 8 roles created');
        $this->command->info('  - ' . count($permissions) . ' permissions created');
    }
}
