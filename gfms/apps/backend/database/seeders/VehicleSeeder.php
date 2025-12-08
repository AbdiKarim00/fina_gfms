<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        $motOrgId = DB::table('organizations')->where('code', 'MOT')->value('id');
        $mohOrgId = DB::table('organizations')->where('code', 'MOH')->value('id');
        $ncgOrgId = DB::table('organizations')->where('code', 'NCG')->value('id');

        $vehicles = [
            [
                'organization_id' => $motOrgId,
                'registration_number' => 'KCA 001A',
                'make' => 'Toyota',
                'model' => 'Land Cruiser V8',
                'year' => 2023,
                'type' => 'suv',
                'fuel_type' => 'diesel',
                'capacity' => 7,
                'status' => 'available',
                'mileage' => 15000,
                'last_service_date' => now()->subDays(30),
                'next_service_date' => now()->addDays(60),
                'insurance_expiry' => now()->addMonths(6),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $motOrgId,
                'registration_number' => 'KCA 002B',
                'make' => 'Mercedes-Benz',
                'model' => 'E-Class',
                'year' => 2022,
                'type' => 'sedan',
                'fuel_type' => 'petrol',
                'capacity' => 5,
                'status' => 'available',
                'mileage' => 25000,
                'last_service_date' => now()->subDays(15),
                'next_service_date' => now()->addDays(75),
                'insurance_expiry' => now()->addMonths(8),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $mohOrgId,
                'registration_number' => 'KCB 101C',
                'make' => 'Toyota',
                'model' => 'Hilux Double Cab',
                'year' => 2023,
                'type' => 'pickup',
                'fuel_type' => 'diesel',
                'capacity' => 5,
                'status' => 'in_use',
                'mileage' => 8000,
                'last_service_date' => now()->subDays(45),
                'next_service_date' => now()->addDays(45),
                'insurance_expiry' => now()->addMonths(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $mohOrgId,
                'registration_number' => 'KCB 102D',
                'make' => 'Nissan',
                'model' => 'Patrol',
                'year' => 2021,
                'type' => 'suv',
                'fuel_type' => 'diesel',
                'capacity' => 7,
                'status' => 'maintenance',
                'mileage' => 45000,
                'last_service_date' => now()->subDays(5),
                'next_service_date' => now()->addDays(85),
                'insurance_expiry' => now()->addMonths(4),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $ncgOrgId,
                'registration_number' => 'KCC 201E',
                'make' => 'Isuzu',
                'model' => 'D-Max',
                'year' => 2022,
                'type' => 'pickup',
                'fuel_type' => 'diesel',
                'capacity' => 5,
                'status' => 'available',
                'mileage' => 32000,
                'last_service_date' => now()->subDays(20),
                'next_service_date' => now()->addDays(70),
                'insurance_expiry' => now()->addMonths(7),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $ncgOrgId,
                'registration_number' => 'KCC 202F',
                'make' => 'Toyota',
                'model' => 'Prado',
                'year' => 2023,
                'type' => 'suv',
                'fuel_type' => 'diesel',
                'capacity' => 7,
                'status' => 'available',
                'mileage' => 12000,
                'last_service_date' => now()->subDays(10),
                'next_service_date' => now()->addDays(80),
                'insurance_expiry' => now()->addMonths(11),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('vehicles')->insert($vehicles);

        $this->command->info('Vehicles seeded successfully!');
    }
}
