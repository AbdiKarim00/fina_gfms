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
                'registration_number' => 'GKB 671S',
                'make' => 'Toyota',
                'model' => 'Land Cruiser',
                'year' => 2017,
                'fuel_type' => 'diesel',
                'capacity' => 7,
                'status' => 'active',
                'mileage' => 85000,
                'engine_number' => '1HZ-0879638',
                'chassis_number' => 'JTELB71J50-7726026',
                'current_location' => 'POOL',
                'responsible_officer' => 'MULEI',
                'has_log_book' => true,
                'purchase_year' => 2017,
                'notes' => 'SERVICEABLE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $motOrgId,
                'registration_number' => 'GKB 789S',
                'make' => 'Toyota',
                'model' => 'Land Cruiser Prado',
                'year' => 2017,
                'fuel_type' => 'diesel',
                'capacity' => 7,
                'status' => 'active',
                'mileage' => 92000,
                'engine_number' => '1KD-2751777',
                'chassis_number' => 'JTEBH3FJ30-K193277',
                'current_location' => 'PS OFFICE',
                'responsible_officer' => 'PS\'s SECRETARY',
                'has_log_book' => true,
                'purchase_year' => 2017,
                'notes' => 'SERVICEABLE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $mohOrgId,
                'registration_number' => 'GKB 963V',
                'make' => 'Nissan',
                'model' => 'X-Trail',
                'year' => 2019,
                'fuel_type' => 'petrol',
                'capacity' => 5,
                'status' => 'active',
                'mileage' => 45000,
                'engine_number' => 'MR20663553C',
                'chassis_number' => 'JN1JANT32Z0012493',
                'current_location' => 'GOVERNMENT DIGITAL PAYMENTS',
                'responsible_officer' => 'DIRECTOR, GDP',
                'has_log_book' => true,
                'purchase_year' => 2019,
                'notes' => 'SERVICEABLE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $mohOrgId,
                'registration_number' => 'GKB 191E',
                'make' => 'Toyota',
                'model' => 'Land Cruiser Prado',
                'year' => 2013,
                'fuel_type' => 'diesel',
                'capacity' => 7,
                'status' => 'maintenance',
                'mileage' => 145000,
                'engine_number' => '1KD-2299357',
                'chassis_number' => 'JTEBH3FJ70-K114449',
                'current_location' => 'CFAO MOTORS WORKSHOP',
                'responsible_officer' => 'CHAIR, PENDING BILLS VERIFICATION COMMITTEE',
                'has_log_book' => true,
                'purchase_year' => 2013,
                'notes' => 'AWAITING MAJOR SERVICE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $ncgOrgId,
                'registration_number' => 'GKB 278V',
                'make' => 'Toyota',
                'model' => 'Corolla',
                'year' => 2018,
                'fuel_type' => 'petrol',
                'capacity' => 5,
                'status' => 'active',
                'mileage' => 62000,
                'engine_number' => '2ZR-W031856',
                'chassis_number' => 'AHTBF3JE500012943',
                'current_location' => 'PIPM',
                'responsible_officer' => 'MULEI',
                'has_log_book' => true,
                'purchase_year' => 2018,
                'notes' => 'SERVICEABLE',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'organization_id' => $ncgOrgId,
                'registration_number' => 'GKB 167E',
                'make' => 'Nissan',
                'model' => 'X-Trail',
                'year' => 2013,
                'fuel_type' => 'petrol',
                'capacity' => 5,
                'status' => 'inactive',
                'mileage' => 185000,
                'engine_number' => 'QR25625518B',
                'chassis_number' => 'JN1TANT31Z0104779',
                'current_location' => 'POOL',
                'responsible_officer' => 'MULEI',
                'has_log_book' => true,
                'purchase_year' => 2013,
                'notes' => 'UNSERVICEABLE - MARKED FOR DISPOSAL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('vehicles')->insert($vehicles);

        $this->command->info('Vehicles seeded successfully!');
    }
}
