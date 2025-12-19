# Frontend-Backend Architecture Alignment

This document describes how the frontend and backend directory structures are aligned and consistent with each other in terms of organization, naming conventions, and architectural patterns. Both sides follow the same modular structure based on the entity-role-based architecture.

## Overview

The frontend and backend architectures are designed to mirror each other, ensuring consistency and ease of development. This alignment facilitates:

1. **Consistent Naming Conventions**: Same directory names and structures across both sides
2. **Modular Organization**: Corresponding directories for components, services, and modules
3. **Role-Based Structure**: Matching the entity-role hierarchy defined in policy documents
4. **Scalable Architecture**: Easy to extend and maintain as the system grows

## Directory Structure Alignment

### Backend Structure (`/apps/backend/app/Entities`)
```
Entities/
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

### Frontend Structure (`/apps/frontend/src/entities`)
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

## Component Mapping

### 1. Entity Components
Each entity directory in the backend has a corresponding directory in the frontend:

| Backend Entity | Frontend Entity | Purpose |
|----------------|-----------------|---------|
| `/Entities/GovernmentOfficials` | `/entities/GovernmentOfficials` | Components related to government officials |
| `/Entities/Vehicles` | `/entities/Vehicles` | Vehicle-related UI components and services |
| `/Entities/Workflows` | `/entities/Workflows` | Workflow visualization and management components |

### 2. Role-Based Components
Components are organized by user roles to ensure proper access control:

| Role Category | Backend Location | Frontend Location | Description |
|---------------|------------------|-------------------|-------------|
| Administrative | `/Entities/Roles/Administrative` | `/entities/Roles/Administrative` | Components for Cabinet Secretary, Principal Secretaries |
| Operational | `/Entities/Roles/Operational` | `/entities/Roles/Operational` | Components for Fleet Managers, CMTE Officials |
| Field Operations | `/Entities/GovernmentOfficials/FieldOperations` | `/entities/GovernmentOfficials/FieldOperations` | Components for Drivers, GVCU Personnel |

### 3. Workflow Components
Workflow-specific components are organized consistently:

| Workflow | Backend Location | Frontend Location | Description |
|----------|------------------|-------------------|-------------|
| Vehicle Acquisition | `/Entities/Workflows/VehicleAcquisition` | `/entities/Workflows/VehicleAcquisition` | Procurement and assignment workflow components |
| Maintenance | `/Entities/Workflows/Maintenance` | `/entities/Workflows/Maintenance` | Vehicle maintenance scheduling and tracking |
| Incident Reporting | `/Entities/Workflows/IncidentReporting` | `/entities/Workflows/IncidentReporting` | Accident and issue reporting components |

## Service Layer Alignment

### Backend Services
Located in `/apps/backend/app/Services`

### Frontend Services
Located in `/apps/frontend/src/entities/*/services` or `/apps/frontend/src/services`

The service layer follows the same entity-role structure:
- `/apps/frontend/src/entities/GovernmentOfficials/services/` - Services for government official management
- `/apps/frontend/src/entities/Vehicles/services/` - Vehicle-related API services
- `/apps/frontend/src/entities/Workflows/services/` - Workflow orchestration services

## Component Structure

### Backend Components
Located in `/apps/backend/app/Http/Controllers` and `/apps/backend/app/Entities`

### Frontend Components
Located in `/apps/frontend/src/entities/*/*/components`

Each entity directory contains:
```
entities/{EntityCategory}/{EntityType}/
├── components/          # UI components
├── services/            # API services
├── hooks/               # Custom React hooks
├── utils/               # Utility functions
├── types/               # TypeScript interfaces and types
└── tests/               # Component tests
```

## Security Alignment

### Backend Security
Located in `/apps/backend/app/Entities/Security`

### Frontend Security
Located in `/apps/frontend/src/entities/Security`

Matching security components:
- `/apps/backend/app/Entities/Security/Authentication` ↔ `/apps/frontend/src/entities/Security/Authentication`
- `/apps/backend/app/Entities/Security/Authorization` ↔ `/apps/frontend/src/entities/Security/Authorization`

## Integration Points

### Backend Integrations
Located in `/apps/backend/app/Entities/Integrations`

### Frontend Integrations
Located in `/apps/frontend/src/entities/Integrations`

Consistent external system integration:
- `/apps/backend/app/Entities/Integrations/External/NTSA` ↔ `/apps/frontend/src/entities/Integrations/External/NTSA`
- `/apps/backend/app/Entities/Integrations/Internal/IFMIS` ↔ `/apps/frontend/src/entities/Integrations/Internal/IFMIS`

## Benefits of Alignment

1. **Developer Productivity**: Familiar structure reduces cognitive load
2. **Maintainability**: Changes in one layer are easy to propagate to the other
3. **Scalability**: Adding new entities or roles follows established patterns
4. **Consistency**: Uniform naming and organization across the entire system
5. **Traceability**: Easy to trace functionality from UI to database
6. **Team Collaboration**: Clear ownership and responsibility boundaries

## Implementation Guidelines

### Adding New Entities
1. Create corresponding directories in both frontend and backend
2. Follow the established naming conventions
3. Implement matching service layers
4. Ensure proper security integration
5. Document the new entity in both systems

### Extending Existing Entities
1. Add new components to the appropriate entity directory
2. Update both frontend and backend simultaneously
3. Maintain consistent API contracts
4. Ensure role-based access control is properly implemented

### Code Organization Best Practices
1. Keep related components, services, and utilities in the same directory
2. Use index files to export module contents
3. Follow consistent naming patterns for files and functions
4. Implement proper error handling in both layers
5. Maintain comprehensive documentation for each entity

This alignment ensures that the Government Fleet Management System maintains consistency between frontend and backend implementations while following the entity-role-based architecture defined in the Government Transport Policy (2024).