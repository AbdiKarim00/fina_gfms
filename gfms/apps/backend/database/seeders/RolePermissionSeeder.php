<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Clear existing data to ensure clean seed
        DB::table('permissions.role_has_permissions')->delete();
        DB::table('permissions.model_has_roles')->delete();
        DB::table('permissions.model_has_permissions')->delete();
        DB::table('permissions.roles')->delete();
        DB::table('permissions.permissions')->delete();
        
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
            
            // Cabinet Secretary specific permissions
            'full_system_oversight',
            'view_policy_compliance',
            'monitor_budget_execution',
            'audit_user_accounts',
            'intervene_in_workflows',
            'access_strategic_dashboards',
        ];
        
        // Insert permissions into the database
        foreach ($permissions as $permission) {
            DB::table('permissions.permissions')->insert([
                'name' => $permission,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // Define roles and their permissions
        $roles = [
            'Super Admin' => $permissions, // All permissions
            
            'Cabinet Secretary' => [
                'full_system_oversight',
                'view_policy_compliance',
                'monitor_budget_execution',
                'audit_user_accounts',
                'intervene_in_workflows',
                'access_strategic_dashboards',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_users',
                'view_organizations',
                'view_reports',
                'export_reports',
                'view_analytics',
                'view_audit_logs',
                'view_policy_documents',
                'view_budget_allocations',
                'view_performance_metrics',
            ],
            
            'Principal Secretary' => [
                'department_admin',
                'major_procurement_approval',
                'view_vehicles',
                'create_vehicles',
                'edit_vehicles',
                'delete_vehicles',
                'assign_vehicles',
                'view_drivers',
                'create_drivers',
                'edit_drivers',
                'delete_drivers',
                'assign_drivers',
                'view_trips',
                'create_trips',
                'edit_trips',
                'delete_trips',
                'approve_trips',
                'view_maintenance',
                'create_maintenance',
                'edit_maintenance',
                'delete_maintenance',
                'approve_maintenance',
                'view_fuel',
                'create_fuel',
                'edit_fuel',
                'delete_fuel',
                'approve_fuel',
                'view_inspections',
                'create_inspections',
                'edit_inspections',
                'delete_inspections',
                'approve_inspections',
                'view_users',
                'create_users',
                'edit_users',
                'assign_roles',
                'view_organizations',
                'edit_organizations',
                'view_reports',
                'export_reports',
                'view_analytics',
            ],
            
            'Accounting Officer' => [
                'financial_admin',
                'asset_management',
                'budget_allocation',
                'budget_approval',
                'view_vehicles',
                'edit_vehicles',
                'view_drivers',
                'edit_drivers',
                'view_trips',
                'approve_trips',
                'view_maintenance',
                'approve_maintenance',
                'view_fuel',
                'approve_fuel',
                'view_inspections',
                'approve_inspections',
                'view_users',
                'edit_users',
                'view_organizations',
                'view_reports',
                'export_reports',
            ],
            
            'GFMD Director' => [
                'operations_admin',
                'user_management',
                'policy_enforcement',
                'view_vehicles',
                'create_vehicles',
                'edit_vehicles',
                'delete_vehicles',
                'assign_vehicles',
                'view_drivers',
                'create_drivers',
                'edit_drivers',
                'delete_drivers',
                'assign_drivers',
                'view_trips',
                'create_trips',
                'edit_trips',
                'delete_trips',
                'approve_trips',
                'view_maintenance',
                'create_maintenance',
                'edit_maintenance',
                'delete_maintenance',
                'approve_maintenance',
                'view_fuel',
                'create_fuel',
                'edit_fuel',
                'delete_fuel',
                'approve_fuel',
                'view_inspections',
                'create_inspections',
                'edit_inspections',
                'delete_inspections',
                'approve_inspections',
                'view_users',
                'create_users',
                'edit_users',
                'delete_users',
                'assign_roles',
                'view_organizations',
                'edit_organizations',
                'view_reports',
                'export_reports',
                'view_analytics',
            ],
            
            'Fleet Manager' => [
                'operations_manager',
                'vehicle_assignment',
                'driver_management',
                'trip_scheduling',
                'maintenance_coordination',
                'fuel_management',
                'inspection_scheduling',
                'view_vehicles',
                'create_vehicles',
                'edit_vehicles',
                'assign_vehicles',
                'view_drivers',
                'create_drivers',
                'edit_drivers',
                'assign_drivers',
                'view_trips',
                'create_trips',
                'edit_trips',
                'delete_trips',
                'approve_trips',
                'view_maintenance',
                'create_maintenance',
                'edit_maintenance',
                'approve_maintenance',
                'view_fuel',
                'create_fuel',
                'edit_fuel',
                'approve_fuel',
                'view_inspections',
                'create_inspections',
                'edit_inspections',
                'approve_inspections',
                'view_users',
                'edit_users',
                'view_organizations',
                'view_reports',
            ],
            
            'CMTE Official' => [
                'transport_officer',
                'trip_approval',
                'driver_supervision',
                'vehicle_inspection',
                'view_vehicles',
                'edit_vehicles',
                'view_drivers',
                'edit_drivers',
                'view_trips',
                'create_trips',
                'edit_trips',
                'approve_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_users',
                'view_organizations',
                'view_reports',
            ],
            
            'GVCU Officer' => [
                'vehicle_controller',
                'maintenance_approval',
                'fuel_approval',
                'inspection_verification',
                'view_vehicles',
                'edit_vehicles',
                'view_drivers',
                'edit_drivers',
                'view_trips',
                'view_maintenance',
                'approve_maintenance',
                'view_fuel',
                'approve_fuel',
                'view_inspections',
                'approve_inspections',
                'view_users',
                'view_organizations',
                'view_reports',
            ],
            
            'Authorized Driver' => [
                'driver_operations',
                'trip_execution',
                'vehicle_operation',
                'incident_reporting',
                'view_assigned_trips',
                'update_trip_status',
                'report_vehicle_issues',
                'view_personal_schedule',
            ],
            
            'M&E Specialist' => [
                'monitoring_evaluation',
                'performance_analysis',
                'data_collection',
                'report_generation',
                'kpi_tracking',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_users',
                'view_organizations',
                'view_reports',
                'export_reports',
                'view_analytics',
            ],
            
            'Audit Officer' => [
                'audit_compliance',
                'financial_review',
                'process_verification',
                'compliance_monitoring',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_users',
                'view_organizations',
                'view_reports',
                'export_reports',
                'view_audit_logs',
            ],
            
            'Policy Analyst' => [
                'policy_development',
                'regulatory_compliance',
                'strategic_planning',
                'research_analysis',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_users',
                'view_organizations',
                'view_reports',
                'export_reports',
                'view_analytics',
            ],
        ];
        
        // Insert roles and assign permissions
        foreach ($roles as $roleName => $rolePermissions) {
            // Insert role
            $roleId = DB::table('permissions.roles')->insertGetId([
                'name' => $roleName,
                'guard_name' => 'web',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            // Get permission IDs
            $permissionIds = DB::table('permissions.permissions')
                ->whereIn('name', $rolePermissions)
                ->pluck('id')
                ->toArray();
            
            // Assign permissions to role
            foreach ($permissionIds as $permissionId) {
                DB::table('permissions.role_has_permissions')->insert([
                    'permission_id' => $permissionId,
                    'role_id' => $roleId,
                ]);
            }
        }
        
        $this->command->info('✓ Roles and permissions created successfully!');
        $this->command->info('  - ' . count($roles) . ' roles created');
        $this->command->info('  - ' . count($permissions) . ' permissions created');
    }
}
