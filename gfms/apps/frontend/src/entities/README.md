# Frontend Entities Structure

This directory contains the frontend implementation of the entity-role-based architecture, aligned with the backend structure at `/apps/backend/app/Entities`.

## Structure Overview

The frontend entities structure mirrors the backend architecture to ensure consistency and ease of development:

```
entities/
├── GovernmentOfficials/
│   ├── NationalLeadership/
│   ├── SeniorAdministration/
│   ├── DepartmentHeads/
│   ├── CountyLeadership/
│   ├── OperationalManagement/
│   └── FieldOperations/
├── GovernmentDepartments/
│   ├── NationalLevel/
│   ├── CountyLevel/
│   └── ExternalEntities/
├── Vehicles/
├── PolicyImplementation/
├── RegulatoryBodies/
├── ServiceProviders/
├── TechnologySystems/
├── Roles/
│   ├── Administrative/
│   ├── Operational/
│   └── MonitoringReporting/
├── Workflows/
│   ├── VehicleAcquisition/
│   ├── Maintenance/
│   └── IncidentReporting/
├── Reports/
│   ├── RealTimeTracking/
│   ├── PeriodicCompliance/
│   └── StrategicPolicy/
├── Integrations/
│   ├── Internal/
│   └── External/
└── Security/
    ├── Authentication/
    ├── Authorization/
    └── DataProtection/
```

## Directory Organization

Each entity directory follows a consistent structure:

```
entities/{EntityCategory}/{EntityType}/
├── components/          # React components
├── services/            # API service classes
├── hooks/               # Custom React hooks
├── utils/               # Utility functions
├── types/               # TypeScript interfaces and types
└── tests/               # Component and unit tests
```

## Component Structure

Components are organized by entity and role to ensure proper separation of concerns:

### Government Officials
- `OperationalManagement/components/FleetManagerDashboard.tsx` - Dashboard for fleet managers
- `FieldOperations/components/DriverPortal.tsx` - Portal for authorized drivers
- `NationalLeadership/components/PolicyDashboard.tsx` - Dashboard for cabinet secretaries

### Vehicles
- `Vehicles/components/VehicleList.tsx` - List of vehicles
- `Vehicles/components/VehicleDetails.tsx` - Detailed vehicle information
- `Vehicles/components/VehicleAssignment.tsx` - Vehicle assignment interface

### Workflows
- `Workflows/VehicleAcquisition/components/ProcurementForm.tsx` - Vehicle procurement form
- `Workflows/Maintenance/components/MaintenanceScheduler.tsx` - Maintenance scheduling interface
- `Workflows/IncidentReporting/components/IncidentReport.tsx` - Incident reporting form

## Service Layer

Each entity has corresponding service classes that communicate with the backend API:

```typescript
// Example service structure
import { apiClient } from '../../../../services/api';
import { Vehicle } from '../../types/Vehicle';

export class FleetManagerService {
  static async getAvailableVehicles(): Promise<Vehicle[]> {
    const response = await apiClient.get<ApiResponse<Vehicle[]>>('/api/fleet-manager/vehicles/available');
    return response.data.data || [];
  }
}
```

## Type Definitions

TypeScript interfaces are defined in the `types/` directory of each entity:

```typescript
// entities/Vehicles/types/Vehicle.ts
export interface Vehicle {
  id: number;
  registrationNumber: string;
  make: string;
  model: string;
  year: number;
  // ... other properties
}
```

## Alignment with Backend

This frontend structure directly corresponds to the backend entity structure:

| Frontend Directory | Backend Directory | Purpose |
|-------------------|-------------------|---------|
| `entities/GovernmentOfficials/OperationalManagement/` | `app/Entities/GovernmentOfficials/OperationalManagement/` | Fleet manager functionality |
| `entities/Vehicles/` | `app/Entities/Vehicles/` | Vehicle management |
| `entities/Workflows/VehicleAcquisition/` | `app/Entities/Workflows/VehicleAcquisition/` | Procurement workflows |

## Benefits

1. **Consistency**: Same organizational structure as backend
2. **Maintainability**: Easy to locate related frontend and backend components
3. **Scalability**: Simple to add new entities following established patterns
4. **Developer Experience**: Familiar structure reduces cognitive load
5. **Traceability**: Clear mapping between UI components and backend entities

## Adding New Entities

To add a new entity:

1. Create the corresponding directory structure in both frontend and backend
2. Implement the backend entity class
3. Create frontend components, services, and types
4. Ensure proper API integration
5. Follow the established naming conventions
6. Document the new entity

This structure ensures that the Government Fleet Management System maintains consistency between frontend and backend implementations while following the entity-role-based architecture defined in the Government Transport Policy (2024).