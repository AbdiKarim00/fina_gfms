<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Vehicle Management
            'view_vehicles',
            'create_vehicles',
            'edit_vehicles',
            'delete_vehicles',
            
            // Driver Management
            'view_drivers',
            'create_drivers',
            'edit_drivers',
            'delete_drivers',
            
            // Booking Management
            'view_bookings',
            'create_bookings',
            'approve_bookings',
            'cancel_bookings',
            
            // Maintenance
            'view_maintenance',
            'create_maintenance',
            'edit_maintenance',
            
            // Fuel Management
            'view_fuel',
            'create_fuel',
            'approve_fuel',
            
            // Reports
            'view_reports',
            'export_reports',
            
            // User Management
            'view_users',
            'create_users',
            'edit_users',
            'delete_users',
            
            // Organization Management
            'view_organizations',
            'create_organizations',
            'edit_organizations',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
            'view_vehicles', 'create_vehicles', 'edit_vehicles',
            'view_drivers', 'create_drivers', 'edit_drivers',
            'view_bookings', 'create_bookings', 'approve_bookings',
            'view_maintenance', 'create_maintenance',
            'view_fuel', 'create_fuel', 'approve_fuel',
            'view_reports', 'export_reports',
            'view_users', 'create_users', 'edit_users',
        ]);

        $fleetManager = Role::create(['name' => 'fleet_manager']);
        $fleetManager->givePermissionTo([
            'view_vehicles', 'edit_vehicles',
            'view_drivers', 'edit_drivers',
            'view_bookings', 'approve_bookings',
            'view_maintenance', 'create_maintenance',
            'view_fuel', 'approve_fuel',
            'view_reports',
        ]);

        $driver = Role::create(['name' => 'driver']);
        $driver->givePermissionTo([
            'view_vehicles',
            'view_bookings',
            'view_maintenance',
            'create_fuel',
        ]);

        $user = Role::create(['name' => 'user']);
        $user->givePermissionTo([
            'view_vehicles',
            'create_bookings',
            'view_bookings',
        ]);

        $this->command->info('Roles and permissions created successfully!');
    }
}
