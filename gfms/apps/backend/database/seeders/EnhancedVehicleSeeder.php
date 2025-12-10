<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Organization;
use Illuminate\Support\Facades\DB;

class EnhancedVehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing vehicles
        Vehicle::truncate();
        
        $csvFile = '/Users/abu/Final GFMS/MV-REGISTER-_2_.csv';
        
        if (!file_exists($csvFile)) {
            $this->command->error('CSV file not found: ' . $csvFile);
            return;
        }
        
        $organization = Organization::first();
        if (!$organization) {
            $this->command->error('No organization found. Please run OrganizationSeeder first.');
            return;
        }
        
        $handle = fopen($csvFile, 'r');
        $header = fgetcsv($handle); // Skip empty first row
        $header = fgetcsv($handle); // Get actual headers
        
        $vehicleCount = 0;
        $statusMapping = [
            'SERVICEABLE' => 'active',
            'UNSERVICEABLE' => 'maintenance',
            'NOT SERVICEABLE-REQUIRES MAJOR REPAIRS' => 'out_of_service',
            'AWAITING MAJOR SERVICE' => 'maintenance',
            'LEASE VEHICLE' => 'active',
        ];
        
        while (($row = fgetcsv($handle)) !== false) {
            if (empty($row[1]) || $row[1] === 'S/No. ') continue; // Skip empty or header rows
            
            $registrationNumber = trim($row[1], '"');
            $makeModel = trim($row[6], '"');
            $yearOfPurchase = trim($row[7], '"');
            $engineNumber = trim($row[3], '"');
            $chassisNumber = trim($row[4], '"');
            $originalLocation = trim($row[9], '"');
            $currentLocation = trim($row[10], '"');
            $responsibleOfficer = trim($row[19], '"');
            $assetCondition = trim($row[20], '"');
            $hasLogBook = trim($row[21], '"') === 'Y';
            $notes = trim($row[22], '"');
            
            // Skip if no registration number
            if (empty($registrationNumber) || $registrationNumber === 'NA') continue;
            
            // Parse make and model
            $makeModelParts = explode('-', $makeModel);
            $make = 'Toyota'; // Default, most are Toyota
            $model = $makeModel;
            
            if (str_contains(strtoupper($makeModel), 'LAND CRUISER')) {
                $make = 'Toyota';
                $model = str_contains(strtoupper($makeModel), 'PRADO') ? 'Land Cruiser Prado' : 'Land Cruiser';
            } elseif (str_contains(strtoupper($makeModel), 'FORTUNER')) {
                $make = 'Toyota';
                $model = 'Fortuner';
            } elseif (str_contains(strtoupper($makeModel), 'HIACE')) {
                $make = 'Toyota';
                $model = 'Hiace';
            } elseif (str_contains(strtoupper($makeModel), 'NISSAN')) {
                $make = 'Nissan';
                $model = 'X-Trail';
            } elseif (str_contains(strtoupper($makeModel), 'LANDROVER')) {
                $make = 'Land Rover';
                $model = 'Defender';
            } elseif (str_contains(strtoupper($makeModel), 'PEUGEOT')) {
                $make = 'Peugeot';
                $model = '504';
            } elseif (str_contains(strtoupper($makeModel), 'ISUZU')) {
                $make = 'Isuzu';
                $model = 'Trooper';
            }
            
            // Determine status
            $status = 'active'; // Default
            if (isset($statusMapping[$assetCondition])) {
                $status = $statusMapping[$assetCondition];
            } elseif (str_contains(strtoupper($notes), 'DISPOSAL')) {
                $status = 'disposed';
            } elseif (str_contains(strtoupper($notes), 'UNSERVICEABLE')) {
                $status = 'out_of_service';
            } elseif (str_contains(strtoupper($assetCondition), 'UNSERVICEABLE')) {
                $status = 'out_of_service';
            }
            
            // Determine capacity based on model
            $capacity = match(true) {
                str_contains(strtolower($model), 'land cruiser') && !str_contains(strtolower($model), 'prado') => 8,
                str_contains(strtolower($model), 'prado') => 7,
                str_contains(strtolower($model), 'fortuner') => 7,
                str_contains(strtolower($model), 'hiace') => 14,
                str_contains(strtolower($model), 'x-trail') => 5,
                str_contains(strtolower($model), 'defender') => 5,
                str_contains(strtolower($model), '504') => 5,
                str_contains(strtolower($model), 'trooper') => 7,
                default => 5
            };
            
            // Determine fuel type
            $fuelType = match(true) {
                str_contains(strtolower($model), 'land cruiser') => 'diesel',
                str_contains(strtolower($model), 'prado') => 'diesel',
                str_contains(strtolower($model), 'fortuner') => 'diesel',
                str_contains(strtolower($model), 'hiace') => 'diesel',
                default => 'petrol'
            };
            
            // Parse year
            $year = is_numeric($yearOfPurchase) ? (int)$yearOfPurchase : null;
            
            try {
                Vehicle::create([
                    'registration_number' => $registrationNumber,
                    'make' => $make,
                    'model' => $model,
                    'year' => $year,
                    'engine_number' => $engineNumber !== 'NA' ? $engineNumber : null,
                    'chassis_number' => $chassisNumber !== 'NA' ? $chassisNumber : null,
                    'fuel_type' => $fuelType,
                    'status' => $status,
                    'capacity' => $capacity,
                    'current_location' => $currentLocation !== 'NA' ? $currentLocation : null,
                    'original_location' => $originalLocation !== 'NA' ? $originalLocation : null,
                    'responsible_officer' => $responsibleOfficer !== 'NA' ? $responsibleOfficer : null,
                    'has_log_book' => $hasLogBook,
                    'notes' => $notes !== 'NA' ? $notes : null,
                    'organization_id' => $organization->id,
                ]);
                
                $vehicleCount++;
            } catch (\Exception $e) {
                $this->command->warn("Failed to create vehicle {$registrationNumber}: " . $e->getMessage());
            }
        }
        
        fclose($handle);
        
        $this->command->info("✓ Created {$vehicleCount} vehicles from CSV data");
        
        // Show status distribution
        $statusCounts = Vehicle::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();
            
        $this->command->info('Vehicle status distribution:');
        foreach ($statusCounts as $statusCount) {
            $this->command->info("  - {$statusCount->status}: {$statusCount->count}");
        }
    }
}