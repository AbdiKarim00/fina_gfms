import React, { Suspense, lazy } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ConfigProvider } from 'antd';
import { AuthProvider } from './contexts/AuthContext';
import { ProtectedRoute } from './components/ProtectedRoute';
import { DashboardLayout } from './layouts/DashboardLayout';
import { PageLoader } from './components/shared/PageLoader';
import { antdTheme } from './theme/antd-theme';

// Lazy load all pages for better performance
const LoginPage = lazy(() => import('./pages/LoginPage').then(m => ({ default: m.LoginPage })));
const VerifyOtpPage = lazy(() => import('./pages/VerifyOtpPage').then(m => ({ default: m.VerifyOtpPage })));
const DashboardPage = lazy(() => import('./pages/DashboardPageV2').then(m => ({ default: m.DashboardPageV2 })));
const VehiclesPage = lazy(() => import('./pages/VehiclesPage').then(m => ({ default: m.VehiclesPage })));

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
                <Route path="dashboard" element={<DashboardPage />} />
                <Route path="vehicles" element={<VehiclesPage />} />
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