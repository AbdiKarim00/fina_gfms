<?php

namespace App\Services;

use App\Services\Contracts\RoleFactoryInterface;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleFactory implements RoleFactoryInterface
{
    /**
     * Create a role with the specified name and permissions
     */
    public function createRole(string $roleName, array $permissions = []): Role
    {
        $role = Role::firstOrCreate(['name' => $roleName]);

        if (! empty($permissions)) {
            // Ensure permissions exist
            $permissionModels = [];
            foreach ($permissions as $permissionName) {
                $permission = Permission::firstOrCreate(['name' => $permissionName, 'guard_name' => 'web']);
                $permissionModels[] = $permission;
            }
            $role->syncPermissions($permissionModels);
        }

        return $role;
    }

    /**
     * Create a hierarchical role with predefined permissions
     */
    public function createHierarchicalRole(string $roleName): Role
    {
        // Define permissions based on role hierarchy
        $permissionMap = [
            'cabinet_secretary' => [
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

            'principal_secretary' => [
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

            'accounting_officer' => [
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

            'gfmd_director' => [
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

            'fleet_manager' => [
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

            'cmte_official' => [
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

            'gvcu_officer' => [
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

            'authorized_driver' => [
                'driver_operations',
                'trip_execution',
                'vehicle_operation',
                'incident_reporting',
                'view_assigned_trips',
                'update_trip_status',
                'report_vehicle_issues',
                'view_personal_schedule',
            ],

            'm_and_e_specialist' => [
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

            'audit_officer' => [
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

            'policy_analyst' => [
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

        $permissions = $permissionMap[$roleName] ?? [];

        return $this->createRole($roleName, $permissions);
    }
}
