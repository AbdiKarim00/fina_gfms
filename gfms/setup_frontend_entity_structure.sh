#!/bin/bash

# Frontend Entity-Role Based Architecture Setup Script
# Aligns with the backend structure at /Users/abu/fina_gfms/gfms/apps/backend/app/Entities

FRONTEND_SRC_PATH="/Users/abu/fina_gfms/gfms/apps/frontend/src"
ENTITIES_PATH="$FRONTEND_SRC_PATH/entities"

echo "Setting up Frontend Entity-Role Based Architecture..."

# Create main entities directory
echo "Creating main entities directory..."
mkdir -p "$ENTITIES_PATH"

# Create main entity directories (matching backend structure)
echo "Creating main entity directories..."
mkdir -p "$ENTITIES_PATH/GovernmentOfficials"
mkdir -p "$ENTITIES_PATH/GovernmentDepartments"
mkdir -p "$ENTITIES_PATH/Vehicles"
mkdir -p "$ENTITIES_PATH/PolicyImplementation"
mkdir -p "$ENTITIES_PATH/RegulatoryBodies"
mkdir -p "$ENTITIES_PATH/ServiceProviders"
mkdir -p "$ENTITIES_PATH/TechnologySystems"

# Create Government Officials hierarchy (matching backend structure)
echo "Creating Government Officials hierarchy..."
mkdir -p "$ENTITIES_PATH/GovernmentOfficials/NationalLeadership"
mkdir -p "$ENTITIES_PATH/GovernmentOfficials/SeniorAdministration"
mkdir -p "$ENTITIES_PATH/GovernmentOfficials/DepartmentHeads"
mkdir -p "$ENTITIES_PATH/GovernmentOfficials/CountyLeadership"
mkdir -p "$ENTITIES_PATH/GovernmentOfficials/OperationalManagement"
mkdir -p "$ENTITIES_PATH/GovernmentOfficials/FieldOperations"

# Create Government Departments hierarchy (matching backend structure)
echo "Creating Government Departments hierarchy..."
mkdir -p "$ENTITIES_PATH/GovernmentDepartments/NationalLevel"
mkdir -p "$ENTITIES_PATH/GovernmentDepartments/CountyLevel"
mkdir -p "$ENTITIES_PATH/GovernmentDepartments/ExternalEntities"

# Create Roles directories (matching backend structure)
echo "Creating Roles directories..."
mkdir -p "$ENTITIES_PATH/Roles/Administrative"
mkdir -p "$ENTITIES_PATH/Roles/Operational"
mkdir -p "$ENTITIES_PATH/Roles/MonitoringReporting"

# Create Workflows directories (matching backend structure)
echo "Creating Workflows directories..."
mkdir -p "$ENTITIES_PATH/Workflows/VehicleAcquisition"
mkdir -p "$ENTITIES_PATH/Workflows/Maintenance"
mkdir -p "$ENTITIES_PATH/Workflows/IncidentReporting"

# Create Reports directories (matching backend structure)
echo "Creating Reports directories..."
mkdir -p "$ENTITIES_PATH/Reports/RealTimeTracking"
mkdir -p "$ENTITIES_PATH/Reports/PeriodicCompliance"
mkdir -p "$ENTITIES_PATH/Reports/StrategicPolicy"

# Create Integrations directories (matching backend structure)
echo "Creating Integrations directories..."
mkdir -p "$ENTITIES_PATH/Integrations/Internal"
mkdir -p "$ENTITIES_PATH/Integrations/External"

# Create Security directories (matching backend structure)
echo "Creating Security directories..."
mkdir -p "$ENTITIES_PATH/Security/Authentication"
mkdir -p "$ENTITIES_PATH/Security/Authorization"
mkdir -p "$ENTITIES_PATH/Security/DataProtection"

echo "Frontend Entity-Role Based Architecture setup complete!"
echo "Directory structure created at: $ENTITIES_PATH"