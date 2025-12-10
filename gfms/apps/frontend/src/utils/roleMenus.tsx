import React from 'react';
import {
  DashboardOutlined,
  CarOutlined,
  SettingOutlined,
  TeamOutlined,
  FileTextOutlined,
  ToolOutlined,
  SafetyOutlined,
  AuditOutlined,
} from '@ant-design/icons';

export interface MenuItem {
  key: string;
  icon: React.ReactNode;
  label: string;
  path: string;
  badge?: string;
}

export const getRoleMenuItems = (roles: string[]): MenuItem[] => {
  const role = roles[0]?.toLowerCase() || '';

  switch (role) {
    case 'super admin':
      return [
        {
          key: '/dashboard',
          icon: <DashboardOutlined />,
          label: 'Dashboard',
          path: '/dashboard',
        },
        {
          key: '/users',
          icon: <TeamOutlined />,
          label: 'User Management',
          path: '/users',
          badge: 'Dev',
        },
        {
          key: '/organizations',
          icon: <AuditOutlined />,
          label: 'Organizations',
          path: '/organizations',
          badge: 'Dev',
        },
        {
          key: '/roles',
          icon: <SafetyOutlined />,
          label: 'Roles & Permissions',
          path: '/roles',
          badge: 'Dev',
        },
        {
          key: '/vehicles',
          icon: <CarOutlined />,
          label: 'Vehicles',
          path: '/vehicles',
        },
        {
          key: '/reports',
          icon: <FileTextOutlined />,
          label: 'System Reports',
          path: '/reports',
          badge: 'Dev',
        },
        {
          key: '/settings',
          icon: <SettingOutlined />,
          label: 'System Settings',
          path: '/settings',
          badge: 'Dev',
        },
      ];

    case 'admin':
      return [
        {
          key: '/dashboard',
          icon: <DashboardOutlined />,
          label: 'Dashboard',
          path: '/dashboard',
        },
        {
          key: '/vehicles',
          icon: <CarOutlined />,
          label: 'Vehicles',
          path: '/vehicles',
        },
        {
          key: '/users',
          icon: <TeamOutlined />,
          label: 'Users',
          path: '/users',
          badge: 'Dev',
        },
        {
          key: '/bookings',
          icon: <FileTextOutlined />,
          label: 'Bookings',
          path: '/bookings',
        },
        {
          key: '/maintenance',
          icon: <ToolOutlined />,
          label: 'Maintenance',
          path: '/maintenance',
          badge: 'Dev',
        },
        {
          key: '/reports',
          icon: <FileTextOutlined />,
          label: 'Reports',
          path: '/reports',
          badge: 'Dev',
        },
      ];

    case 'fleet manager':
      return [
        {
          key: '/dashboard',
          icon: <DashboardOutlined />,
          label: 'Dashboard',
          path: '/dashboard',
        },
        {
          key: '/vehicles',
          icon: <CarOutlined />,
          label: 'Vehicles',
          path: '/vehicles',
        },
        {
          key: '/bookings',
          icon: <FileTextOutlined />,
          label: 'Bookings',
          path: '/bookings',
        },
        {
          key: '/maintenance',
          icon: <ToolOutlined />,
          label: 'Maintenance',
          path: '/maintenance',
          badge: 'Dev',
        },
        {
          key: '/fuel',
          icon: <DashboardOutlined />,
          label: 'Fuel Management',
          path: '/fuel',
          badge: 'Dev',
        },
        {
          key: '/reports',
          icon: <FileTextOutlined />,
          label: 'Reports',
          path: '/reports',
          badge: 'Dev',
        },
      ];

    case 'transport officer':
      return [
        {
          key: '/dashboard',
          icon: <DashboardOutlined />,
          label: 'Dashboard',
          path: '/dashboard',
        },
        {
          key: '/bookings',
          icon: <FileTextOutlined />,
          label: 'My Bookings',
          path: '/bookings',
        },
        {
          key: '/vehicles',
          icon: <CarOutlined />,
          label: 'Available Vehicles',
          path: '/vehicles',
        },
        {
          key: '/reports',
          icon: <FileTextOutlined />,
          label: 'Reports',
          path: '/reports',
          badge: 'Dev',
        },
      ];

    case 'driver':
      return [
        {
          key: '/dashboard',
          icon: <DashboardOutlined />,
          label: 'Dashboard',
          path: '/dashboard',
        },
        {
          key: '/assignments',
          icon: <CarOutlined />,
          label: 'My Assignments',
          path: '/assignments',
          badge: 'Dev',
        },
        {
          key: '/trips',
          icon: <FileTextOutlined />,
          label: 'Trip Logs',
          path: '/trips',
          badge: 'Dev',
        },
        {
          key: '/fuel',
          icon: <DashboardOutlined />,
          label: 'Fuel Records',
          path: '/fuel',
          badge: 'Dev',
        },
      ];

    default:
      return [
        {
          key: '/dashboard',
          icon: <DashboardOutlined />,
          label: 'Dashboard',
          path: '/dashboard',
        },
      ];
  }
};
