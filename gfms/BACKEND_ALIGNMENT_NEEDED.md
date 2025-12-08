# Backend Alignment Status

## Current Status: ⚠️ NEEDS ALIGNMENT

The frontend has advanced features that the backend doesn't fully support yet.

---

## What's Missing in Backend

### 1. Vehicle Model ❌
- **Status**: Doesn't exist
- **Location**: `app/Models/Vehicle.php`
- **Needed**: Eloquent model with relationships and fillable fields

### 2. Database Fields ⚠️ PARTIAL
- **Status**: Table exists but missing fields
- **Current fields** (from migration):
  - ✅ registration_number
  - ✅ make, model, year
  - ✅ color
  - ✅ vin (but frontend uses chassis_number)
  - ✅ engine_number
  - ✅ fuel_type
  - ✅ status
  - ✅ notes
  - ❌ mileage (exists in seeder but not migration)
  - ❌ capacity
  - ❌ purchase_year
  - ❌ current_location
  - ❌ original_location
  - ❌ responsible_officer
  - ❌ has_log_book
  - ❌ chassis_number (frontend uses this instead of vin)
  - ❌ organization_id (seeder uses it but not in migration)

### 3. VehicleController ❌
- **Status**: Doesn't exist
- **Location**: `app/Http/Controllers/VehicleController.php`
- **Needed**: Full CRUD operations
  - index() - List vehicles
  - store() - Create vehicle
  - show() - Get single vehicle
  - update() - Update vehicle
  - destroy() - Delete vehicle

### 4. API Routes ⚠️ PARTIAL
- **Status**: Placeholder routes exist
- **Current**: Only permission-protected placeholders
- **Needed**: Full RESTful routes connected to controller

### 5. Form Requests ❌
- **Status**: Don't exist
- **Needed**:
  - `StoreVehicleRequest.php` - Validation for creating
  - `UpdateVehicleRequest.php` - Validation for updating

---

## Frontend Features Implemented

### ✅ Complete CRUD UI
- List vehicles with table
- View vehicle details modal
- Add vehicle form (15 fields)
- Edit vehicle form
- Delete confirmation modal

### ✅ Advanced Features
- Search and filters
- Statistics dashboard with charts
- Real-time updates
- Form validation

### ✅ Fields Used by Frontend
1. registration_number (required)
2. make (required)
3. model (required)
4. year (required)
5. fuel_type (required)
6. status (required)
7. engine_number (optional)
8. chassis_number (optional) ⚠️ Backend uses "vin"
9. color (optional)
10. mileage (optional)
11. capacity (optional)
12. purchase_year (optional)
13. current_location (optional)
14. responsible_officer (optional)
15. has_log_book (optional)
16. notes (optional)

---

## Required Backend Changes

### Priority 1: Critical (Needed for CRUD to work)

#### 1. Create Vehicle Model
```bash
cd gfms/apps/backend
php artisan make:model Vehicle
```

#### 2. Create Migration for Missing Fields
```bash
php artisan make:migration add_missing_fields_to_vehicles_table
```

Add fields:
- chassis_number (string, nullable)
- mileage (integer, nullable)
- capacity (integer, nullable)
- purchase_year (year, nullable)
- current_location (string, nullable)
- original_location (string, nullable)
- responsible_officer (string, nullable)
- has_log_book (boolean, default true)
- organization_id (foreignId, nullable)

#### 3. Create VehicleController
```bash
php artisan make:controller VehicleController --resource
```

Implement:
- index() - GET /api/v1/vehicles
- store() - POST /api/v1/vehicles
- show() - GET /api/v1/vehicles/{id}
- update() - PUT /api/v1/vehicles/{id}
- destroy() - DELETE /api/v1/vehicles/{id}

#### 4. Create Form Requests
```bash
php artisan make:request StoreVehicleRequest
php artisan make:request UpdateVehicleRequest
```

#### 5. Update API Routes
Replace placeholder routes with:
```php
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('vehicles', VehicleController::class);
});
```

---

### Priority 2: Data Alignment

#### 1. Update VehicleSeeder
- Add missing fields to seed data
- Match frontend field names
- Add more realistic test data

#### 2. Run Migrations
```bash
php artisan migrate:fresh --seed
```

---

### Priority 3: Optional Enhancements

#### 1. Vehicle Statistics Endpoint
```php
GET /api/v1/vehicles/stats
```
Returns:
- Total vehicles
- Active count
- Maintenance count
- Inactive count
- Fuel type distribution
- Average mileage
- Log book compliance

#### 2. Bulk Operations
```php
POST /api/v1/vehicles/bulk-update
DELETE /api/v1/vehicles/bulk-delete
```

#### 3. Export Functionality
```php
GET /api/v1/vehicles/export?format=csv
GET /api/v1/vehicles/export?format=pdf
```

---

## Testing After Alignment

### 1. Test Vehicle CRUD
```bash
# List vehicles
curl -H "Authorization: Bearer {token}" http://localhost:8000/api/v1/vehicles

# Create vehicle
curl -X POST -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"registration_number":"GKB 999Z","make":"Toyota","model":"Land Cruiser","year":2024,"fuel_type":"diesel","status":"active"}' \
  http://localhost:8000/api/v1/vehicles

# Update vehicle
curl -X PUT -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{"mileage":10000}' \
  http://localhost:8000/api/v1/vehicles/1

# Delete vehicle
curl -X DELETE -H "Authorization: Bearer {token}" \
  http://localhost:8000/api/v1/vehicles/1
```

### 2. Test from Frontend
- Login to frontend
- Navigate to Vehicles page
- Try adding a vehicle
- Try editing a vehicle
- Try deleting a vehicle
- Verify stats update

---

## Estimated Time

### Quick Fix (Minimum Viable)
- **Time**: 30-45 minutes
- **Includes**: Model, migration, controller, routes
- **Result**: Basic CRUD working

### Complete Alignment
- **Time**: 1-2 hours
- **Includes**: Everything + validation + stats endpoint
- **Result**: Full feature parity

---

## Next Steps

1. Create Vehicle model
2. Create migration for missing fields
3. Run migration
4. Create VehicleController
5. Update API routes
6. Test CRUD operations
7. Update seeder with new fields
8. Re-seed database

---

**Status**: Ready to implement  
**Priority**: HIGH (frontend depends on this)  
**Risk**: 🟡 MEDIUM (database changes)
