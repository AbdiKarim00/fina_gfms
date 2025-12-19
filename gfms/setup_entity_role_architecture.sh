#!/bin/bash

# Entity-Role Based Architecture Setup Script
# Based on FLEET_MANAGEMENT_SYSTEM_ROLES.md

BASE_PATH="/Users/abu/fina_gfms/gfms/apps/backend/app/Entities"

echo "Setting up Entity-Role Based Architecture..."

# Create main entity directories
echo "Creating main entity directories..."
mkdir -p "$BASE_PATH/GovernmentOfficials"
mkdir -p "$BASE_PATH/GovernmentDepartments"
mkdir -p "$BASE_PATH/Vehicles"
mkdir -p "$BASE_PATH/PolicyImplementation"
mkdir -p "$BASE_PATH/RegulatoryBodies"
mkdir -p "$BASE_PATH/ServiceProviders"
mkdir -p "$BASE_PATH/TechnologySystems"

# Create Government Officials hierarchy
echo "Creating Government Officials hierarchy..."
mkdir -p "$BASE_PATH/GovernmentOfficials/NationalLeadership"
mkdir -p "$BASE_PATH/GovernmentOfficials/SeniorAdministration"
mkdir -p "$BASE_PATH/GovernmentOfficials/DepartmentHeads"
mkdir -p "$BASE_PATH/GovernmentOfficials/CountyLeadership"
mkdir -p "$BASE_PATH/GovernmentOfficials/OperationalManagement"
mkdir -p "$BASE_PATH/GovernmentOfficials/FieldOperations"

# Create Government Departments hierarchy
echo "Creating Government Departments hierarchy..."
mkdir -p "$BASE_PATH/GovernmentDepartments/NationalLevel"
mkdir -p "$BASE_PATH/GovernmentDepartments/CountyLevel"
mkdir -p "$BASE_PATH/GovernmentDepartments/ExternalEntities"

# Create Roles directories
echo "Creating Roles directories..."
mkdir -p "$BASE_PATH/Roles/Administrative"
mkdir -p "$BASE_PATH/Roles/Operational"
mkdir -p "$BASE_PATH/Roles/MonitoringReporting"

# Create Workflows directories
echo "Creating Workflows directories..."
mkdir -p "$BASE_PATH/Workflows/VehicleAcquisition"
mkdir -p "$BASE_PATH/Workflows/Maintenance"
mkdir -p "$BASE_PATH/Workflows/IncidentReporting"

# Create Reports directories
echo "Creating Reports directories..."
mkdir -p "$BASE_PATH/Reports/RealTimeTracking"
mkdir -p "$BASE_PATH/Reports/PeriodicCompliance"
mkdir -p "$BASE_PATH/Reports/StrategicPolicy"

# Create Integrations directories
echo "Creating Integrations directories..."
mkdir -p "$BASE_PATH/Integrations/Internal"
mkdir -p "$BASE_PATH/Integrations/External"

# Create Security directories
echo "Creating Security directories..."
mkdir -p "$BASE_PATH/Security/Authentication"
mkdir -p "$BASE_PATH/Security/Authorization"
mkdir -p "$BASE_PATH/Security/DataProtection"

echo "Entity-Role Based Architecture setup complete!"
echo "Directory structure created at: $BASE_PATH"