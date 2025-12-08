# Fleet Manager Module - Quick Implementation Guide

## Getting Started

This guide provides step-by-step instructions to implement the Fleet Manager modular architecture.

---

## Step 1: Create Folder Structure

```bash
cd gfms/apps/frontend/src

# Create module folders
mkdir -p pages/fleet-manager/{dashboard,vehicles,bookings,maintenance,fuel,reports}
mkdir -p pages/fleet-manager/shared/{components,hooks,types,utils}

# Create component folders
mkdir -p pages/fleet-manager/dashboard/components
mkdir -p pages/fleet-manager/vehicles/components
mkdir -p pages/fleet-manager/bookings/components
mkdir -p pages/fleet-manager/maintenance/components
mkdir -p pages/fleet-manager/fuel/components
mkdir -p pages/fleet-manager/reports/components
```

---

## Step 2: Update Vite Configuration

```typescript
// apps/frontend/vite.config.mts
import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import { visualizer } from 'rollup-plugin-visualizer';

export default defineConfig({
  plugins: [
    react(),
    visualizer({
      open: true,
      gzipSize: true,
      brotliSize: true,
    }),
  ],
  build: {
    rollupOptions: {
      output: {
        manualChunks: {
          'antd-core': ['antd'],
          'antd-icons': ['@ant-design/icons'],
          'vendor': ['react', 'react-dom', 'react-router-dom', 'axios'],
          'fleet-dashboard': ['./src/pages/fleet-manager/dashboard/DashboardPage'],
          'fleet-vehicles': ['./src/pages/fleet-manager/vehicles/VehiclesPage'],
          'fleet-bookings': ['./src/pages/fleet-manager/bookings/BookingsPage'],
          'fleet-maintenance': ['./src/pages/fleet-manager/maintenance/MaintenancePage'],
          'fleet-fuel': ['./src/pages/fleet-manager/fuel/FuelPage'],
          'fleet-reports': ['./src/pages/fleet-manager/reports/ReportsPage'],
        },
      },
    },
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true,
        drop_debugger: true,
      },
    },
    chunkSizeWarningLimit: 500,
  },
  optimizeDeps: {
    include: ['antd', '@ant-design/icons', 'dayjs'],
  },
  server: {
    hmr: true,
  },
});
```

---

## Step 3: Install Additional Dependencies

```bash
cd gfms/apps/frontend

# Install bundle analyzer
npm install --save-dev rollup-plugin-visualizer

# Install React Query for data fetching
npm install @tanstack/react-query

# Install lodash for utilities
npm install lodash
npm install --save-dev @types/lodash
```

---

## Step 4: Create Shared Components

```typescript
// src/pages/fleet-manager/shared/components/PageLoader.tsx
import { Spin } from 'antd';

export const PageLoader = () => (
  <div style={{ 
    display: 'flex', 
    justifyContent: 'center', 
    alignItems: 'center', 
    height: '100vh' 
  }}>
    <Spin size="large" tip="Loading..." />
  </div>
);
```

```typescript
// src/pages/fleet-manager/shared/components/IconRegistry.tsx
import {
  DashboardOutlined,
  CarOutlined,
  CalendarOutlined,
  ToolOutlined,
  DashboardFilled,
  FileTextOutlined,
} from '@ant-design/icons';

export const IconRegistry = {
  dashboard: DashboardOutlined,
  car: CarOutlined,
  calendar: CalendarOutlined,
  tool: ToolOutlined,
  fuel: DashboardFilled,
  report: FileTextOutlined,
};
```

---

## Step 5: Update App.tsx with Lazy Loading

```typescript
// src/App.tsx
import React, { lazy, Suspense } from 'react';
import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { ConfigProvider } from 'antd';
import { QueryClient, QueryClientProvider } from '@tanstack/react-query';
import { AuthProvider } from './contexts/AuthContext';
import { ProtectedRoute } from './components/ProtectedRoute';
import { DashboardLayout } from './layouts/DashboardLayout';
import { LoginPage } from './pages/LoginPage';
import { VerifyOtpPage } from './pages/VerifyOtpPage';
import { antdTheme } from './theme/antd-theme';
import { PageLoader } from './pages/fleet-manager/shared/components/PageLoader';

// Lazy load Fleet Manager modules
const FleetDashboard = lazy(() => import('./pages/fleet-manager/dashboard/DashboardPage'));
const FleetVehicles = lazy(() => import('./pages/fleet-manager/vehicles/VehiclesPage'));
const FleetBookings = lazy(() => import('./pages/fleet-manager/bookings/BookingsPage'));
const FleetMaintenance = lazy(() => import('./pages/fleet-manager/maintenance/MaintenancePage'));
const FleetFuel = lazy(() => import('./pages/fleet-manager/fuel/FuelPage'));
const FleetReports = lazy(() => import('./pages/fleet-manager/reports/ReportsPage'));

// Create React Query client
const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 5 * 60 * 1000, // 5 minutes
      cacheTime: 10 * 60 * 1000, // 10 minutes
      refetchOnWindowFocus: false,
    },
  },
});

const App: React.FC = () => {
  return (
    <ConfigProvider theme={antdTheme}>
      <QueryClientProvider client={queryClient}>
        <BrowserRouter>
          <AuthProvider>
            <Routes>
              <Route path="/login" element={<LoginPage />} />
              <Route path="/verify-otp" element={<VerifyOtpPage />} />
              
              <Route
                path="/fleet-manager"
                element={
                  <ProtectedRoute requiredRole="fleet_manager">
                    <DashboardLayout />
                  </ProtectedRoute>
                }
              >
                <Route index element={<Navigate to="dashboard" replace />} />
                
                <Route path="dashboard" element={
                  <Suspense fallback={<PageLoader />}>
                    <FleetDashboard />
                  </Suspense>
                } />
                
                <Route path="vehicles" element={
                  <Suspense fallback={<PageLoader />}>
                    <FleetVehicles />
                  </Suspense>
                } />
                
                <Route path="bookings" element={
                  <Suspense fallback={<PageLoader />}>
                    <FleetBookings />
                  </Suspense>
                } />
                
                <Route path="maintenance" element={
                  <Suspense fallback={<PageLoader />}>
                    <FleetMaintenance />
                  </Suspense>
                } />
                
                <Route path="fuel" element={
                  <Suspense fallback={<PageLoader />}>
                    <FleetFuel />
                  </Suspense>
                } />
                
                <Route path="reports" element={
                  <Suspense fallback={<PageLoader />}>
                    <FleetReports />
                  </Suspense>
                } />
              </Route>
              
              <Route path="*" element={<Navigate to="/login" replace />} />
            </Routes>
          </AuthProvider>
        </BrowserRouter>
      </QueryClientProvider>
    </ConfigProvider>
  );
};

export default App;
```

---

## Step 6: Test Build Performance

```bash
# Development build
npm run dev

# Production build with analysis
npm run build
npm run analyze

# Check bundle sizes
ls -lh dist/assets/
```

---

## Expected Results

### Before Optimization
- Build time: 3-5 minutes
- Initial bundle: 280KB
- Total bundle: 500KB+

### After Optimization
- Build time: 1-2 minutes ✅
- Initial bundle: 150KB ✅
- Module chunks: 30-50KB each ✅
- Lazy loading: Modules load on demand ✅

---

## Next Steps

1. Implement Dashboard module (see FLEET_MANAGER_MODULAR_ARCHITECTURE.md)
2. Implement Vehicles module
3. Implement Bookings module
4. Continue with remaining modules
5. Add tests and documentation

---

**Ready to start building!** 🚀
