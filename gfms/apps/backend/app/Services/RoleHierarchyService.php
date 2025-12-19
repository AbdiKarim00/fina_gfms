<?php

namespace App\Services;

use App\Models\User;
use App\Services\Contracts\RoleHierarchyServiceInterface;
use Spatie\Permission\Models\Role;

class RoleHierarchyService implements RoleHierarchyServiceInterface
{
    /**
     * Define the role hierarchy structure
     */
    private array $roleHierarchy = [
        'cabinet_secretary' => 1,
        'principal_secretary' => 2,
        'accounting_officer' => 3,
        'gfmd_director' => 4,
        'fleet_manager' => 5,
        'cmte_official' => 6,
        'gvcu_officer' => 7,
        'authorized_driver' => 8,
        'm_and_e_specialist' => 9,
        'audit_officer' => 10,
        'policy_analyst' => 11,
    ];

    /**
     * Get role permissions based on hierarchy
     */
    public function getRolePermissions(string $roleName): array
    {
        $hierarchy = [
            'cabinet_secretary' => [
                'full_system_access',
                'policy_approval',
                'budget_authorization',
                'system_configuration',
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
                'create_organizations',
                'edit_organizations',
                'delete_organizations',
                'view_reports',
                'export_reports',
                'view_analytics',
                'view_audit_logs',
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
                'approve_trips',
                'view_maintenance',
                'create_maintenance',
                'edit_maintenance',
                'approve_maintenance',
                'view_fuel',
                'approve_fuel',
                'view_inspections',
                'create_inspections',
                'edit_inspections',
                'view_reports',
            ],

            'cmte_official' => [
                'technical_specialist',
                'maintenance_approval',
                'inspection_approval',
                'view_vehicles',
                'view_drivers',
                'view_maintenance',
                'approve_maintenance',
                'view_inspections',
                'create_inspections',
                'edit_inspections',
                'approve_inspections',
                'view_reports',
            ],

            'gvcu_officer' => [
                'compliance_monitor',
                'violation_reporting',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_inspections',
                'create_inspections',
                'view_reports',
            ],

            'authorized_driver' => [
                'basic_user',
                'vehicle_reservation',
                'trip_logging',
                'incident_reporting',
                'view_vehicles',
                'view_trips',
                'view_maintenance',
                'create_fuel',
                'view_inspections',
            ],

            'm_and_e_specialist' => [
                'analytics_viewer',
                'kpi_monitoring',
                'data_analysis',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_reports',
                'export_reports',
                'view_analytics',
            ],

            'audit_officer' => [
                'audit_viewer',
                'compliance_verification',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_reports',
                'view_audit_logs',
            ],

            'policy_analyst' => [
                'policy_viewer',
                'recommendation_submission',
                'view_vehicles',
                'view_drivers',
                'view_trips',
                'view_maintenance',
                'view_fuel',
                'view_inspections',
                'view_reports',
            ],
        ];

        return $hierarchy[$roleName] ?? [];
    }

    /**
     * Get user permissions based on their roles
     */
    public function getUserPermissions(User $user): array
    {
        $permissions = [];
        foreach ($user->roles as $role) {
            $permissions = array_merge($permissions, $this->getRolePermissions($role->name));
        }

        return array_unique($permissions);
    }

    /**
     * Check if a user has a role with higher hierarchy
     */
    public function hasHigherRole(User $user, string $roleName): bool
    {
        $userRoles = $user->roles->pluck('name')->toArray();

        $requestedRoleLevel = $this->roleHierarchy[$roleName] ?? PHP_INT_MAX;

        foreach ($userRoles as $userRole) {
            $userRoleLevel = $this->roleHierarchy[$userRole] ?? PHP_INT_MAX;
            if ($userRoleLevel <= $requestedRoleLevel) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all roles with higher hierarchy than the given role
     */
    public function getHigherRoles(string $roleName): array
    {
        $roleLevel = $this->roleHierarchy[$roleName] ?? PHP_INT_MAX;
        $higherRoles = [];

        foreach ($this->roleHierarchy as $role => $level) {
            if ($level < $roleLevel) {
                $higherRoles[] = $role;
            }
        }

        return $higherRoles;
    }

    /**
     * Validate if a role exists in the hierarchy
     */
    public function isValidRole(string $roleName): bool
    {
        return array_key_exists($roleName, $this->roleHierarchy);
    }

    /**
     * Get the hierarchy level of a role
     */
    public function getRoleLevel(string $roleName): int
    {
        return $this->roleHierarchy[$roleName] ?? PHP_INT_MAX;
    }
}
