import { useAuth } from '../contexts/AuthContext';

export interface PermissionCheck {
  canViewVehicles: boolean;
  canCreateVehicles: boolean;
  canEditVehicles: boolean;
  canDeleteVehicles: boolean;
  canEditLimitedVehicleFields: boolean;
  canViewBookings: boolean;
  canCreateBookings: boolean;
  canEditBookings: boolean;
  canDeleteBookings: boolean;
  canApproveBookings: boolean;
  canCancelBookings: boolean;
  isReadOnly: boolean;
  role: string;
}

export const usePermissions = (): PermissionCheck => {
  const { user } = useAuth();
  
  const role = user?.roles?.[0]?.toLowerCase() || '';
  const permissions = user?.permissions || [];

  // Helper function to check if user has a permission
  const hasPermission = (permission: string): boolean => {
    return permissions.includes(permission);
  };

  // Define vehicle permission checks
  const canViewVehicles = hasPermission('view_vehicles');
  const canCreateVehicles = hasPermission('create_vehicles');
  const canEditVehicles = hasPermission('edit_vehicles');
  const canDeleteVehicles = hasPermission('delete_vehicles');

  // Transport Officer has edit permission but only for limited fields
  const canEditLimitedVehicleFields = 
    role === 'transport officer' && canEditVehicles;

  // Define booking permission checks
  const canViewBookings = hasPermission('view_bookings');
  const canCreateBookings = hasPermission('create_bookings');
  const canEditBookings = hasPermission('edit_bookings');
  const canDeleteBookings = hasPermission('delete_bookings');
  const canApproveBookings = hasPermission('approve_bookings');
  const canCancelBookings = hasPermission('cancel_bookings');

  // Driver is read-only
  const isReadOnly = role === 'driver';

  return {
    canViewVehicles,
    canCreateVehicles,
    canEditVehicles,
    canDeleteVehicles,
    canEditLimitedVehicleFields,
    canViewBookings,
    canCreateBookings,
    canEditBookings,
    canDeleteBookings,
    canApproveBookings,
    canCancelBookings,
    isReadOnly,
    role,
  };
};
