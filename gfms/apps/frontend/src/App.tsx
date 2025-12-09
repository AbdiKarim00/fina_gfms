import React, { Suspense, lazy } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ConfigProvider } from 'antd';
import { AuthProvider } from './contexts/AuthContext';
import { ProtectedRoute } from './components/ProtectedRoute';
import { DashboardLayout } from './layouts/DashboardLayout';
import { PageLoader } from './components/shared/PageLoader';
import { antdTheme } from './theme/antd-theme';
import { useAuth } from './contexts/AuthContext';

// Role-based dashboard router component
const RoleDashboard: React.FC = () => {
  const { user } = useAuth();
  const role = user?.roles?.[0]?.toLowerCase() || '';

  switch (role) {
    case 'super admin':
      return <SuperAdminDashboard />;
    case 'admin':
      return <AdminDashboard />;
    case 'fleet manager':
      return <FleetManagerDashboard />;
    case 'transport officer':
      return <TransportOfficerDashboard />;
    case 'driver':
      return <DriverDashboard />;
    default:
      return <FleetManagerDashboard />;
  }
};

// Lazy load all pages for better performance
const LoginPage = lazy(() => import('./pages/LoginPage').then(m => ({ default: m.LoginPage })));
const VerifyOtpPage = lazy(() => import('./pages/VerifyOtpPage').then(m => ({ default: m.VerifyOtpPage })));
const VehiclesPage = lazy(() => import('./pages/VehiclesPageV2').then(m => ({ default: m.VehiclesPageV2 })));

// Role-specific dashboards
const SuperAdminDashboard = lazy(() => import('./pages/dashboards/SuperAdminDashboard').then(m => ({ default: m.SuperAdminDashboard })));
const AdminDashboard = lazy(() => import('./pages/dashboards/AdminDashboard').then(m => ({ default: m.AdminDashboard })));
const FleetManagerDashboard = lazy(() => import('./pages/DashboardPageV2').then(m => ({ default: m.DashboardPageV2 })));
const TransportOfficerDashboard = lazy(() => import('./pages/dashboards/TransportOfficerDashboard').then(m => ({ default: m.TransportOfficerDashboard })));
const DriverDashboard = lazy(() => import('./pages/dashboards/DriverDashboard').then(m => ({ default: m.DriverDashboard })));

// Under development placeholder
const UnderDevelopment = lazy(() => import('./components/shared/UnderDevelopment').then(m => ({ default: m.UnderDevelopment })));

const App: React.FC = () => {
  return (
    <ConfigProvider theme={antdTheme}>
      <BrowserRouter>
        <AuthProvider>
          <Suspense fallback={<PageLoader />}>
            <Routes>
              <Route path="/login" element={<LoginPage />} />
              <Route path="/verify-otp" element={<VerifyOtpPage />} />
              <Route
                path="/"
                element={
                  <ProtectedRoute>
                    <DashboardLayout />
                  </ProtectedRoute>
                }
              >
                <Route index element={<Navigate to="/dashboard" replace />} />
                <Route path="dashboard" element={<RoleDashboard />} />
                <Route path="vehicles" element={<VehiclesPage />} />
                {/* Placeholder routes for under development features */}
                <Route path="users" element={<UnderDevelopment title="User Management" />} />
                <Route path="organizations" element={<UnderDevelopment title="Organizations" />} />
                <Route path="roles" element={<UnderDevelopment title="Roles & Permissions" />} />
                <Route path="bookings" element={<UnderDevelopment title="Bookings" />} />
                <Route path="maintenance" element={<UnderDevelopment title="Maintenance" />} />
                <Route path="fuel" element={<UnderDevelopment title="Fuel Management" />} />
                <Route path="reports" element={<UnderDevelopment title="Reports" />} />
                <Route path="settings" element={<UnderDevelopment title="Settings" />} />
                <Route path="assignments" element={<UnderDevelopment title="Assignments" />} />
                <Route path="trips" element={<UnderDevelopment title="Trip Logs" />} />
              </Route>
              <Route path="*" element={<Navigate to="/dashboard" replace />} />
            </Routes>
          </Suspense>
        </AuthProvider>
      </BrowserRouter>
    </ConfigProvider>
  );
};

export default App;