<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get users and vehicles
        $transportOfficer = DB::table('users')->where('personal_number', '345678')->first();
        $driver = DB::table('users')->where('personal_number', '654321')->first();
        $fleetManager = DB::table('users')->where('personal_number', '234567')->first();
        
        $vehicles = DB::table('vehicles')->limit(3)->get();

        if (!$transportOfficer || !$driver || !$fleetManager || $vehicles->count() < 2) {
            $this->command->warn('Required users or vehicles not found. Skipping booking seeder.');
            return;
        }
        
        // Use available vehicles (cycle through if less than 3)
        $vehicleIds = $vehicles->pluck('id')->toArray();

        // Create sample bookings
        $bookings = [
            // Pending booking 1 (High priority)
            [
                'vehicle_id' => $vehicleIds[0],
                'requester_id' => $transportOfficer->id,
                'driver_id' => $driver->id,
                'start_date' => Carbon::now()->addDays(2)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(2)->setTime(17, 0),
                'purpose' => 'Official visit to Nairobi County Health Department for quarterly review meeting',
                'destination' => 'Nairobi County Health Department',
                'passengers' => 3,
                'status' => 'pending',
                'priority' => 'high',
                'notes' => 'VIP transport required. Director will be attending.',
                'created_at' => Carbon::now()->subHours(2),
                'updated_at' => Carbon::now()->subHours(2),
            ],
            
            // Pending booking 2 (Medium priority)
            [
                'vehicle_id' => $vehicleIds[1] ?? $vehicleIds[0],
                'requester_id' => $transportOfficer->id,
                'driver_id' => null,
                'start_date' => Carbon::now()->addDays(5)->setTime(9, 0),
                'end_date' => Carbon::now()->addDays(5)->setTime(16, 0),
                'purpose' => 'Transport medical supplies to Kiambu County Hospital',
                'destination' => 'Kiambu County Hospital',
                'passengers' => 2,
                'status' => 'pending',
                'priority' => 'medium',
                'notes' => 'Refrigerated transport preferred.',
                'created_at' => Carbon::now()->subHours(5),
                'updated_at' => Carbon::now()->subHours(5),
            ],
            
            // Approved booking
            [
                'vehicle_id' => $vehicleIds[2] ?? $vehicleIds[0],
                'requester_id' => $transportOfficer->id,
                'driver_id' => $driver->id,
                'start_date' => Carbon::now()->addDays(1)->setTime(7, 0),
                'end_date' => Carbon::now()->addDays(1)->setTime(18, 0),
                'purpose' => 'Field visit to rural health centers in Machakos County',
                'destination' => 'Machakos County',
                'passengers' => 4,
                'status' => 'approved',
                'priority' => 'medium',
                'approved_by' => $fleetManager->id,
                'approved_at' => Carbon::now()->subHours(1),
                'notes' => 'Long distance travel. Ensure vehicle is fueled.',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subHours(1),
            ],
            
            // Completed booking
            [
                'vehicle_id' => $vehicleIds[0],
                'requester_id' => $transportOfficer->id,
                'driver_id' => $driver->id,
                'start_date' => Carbon::now()->subDays(2)->setTime(8, 0),
                'end_date' => Carbon::now()->subDays(2)->setTime(17, 0),
                'purpose' => 'Transport staff to training workshop at KEMRI',
                'destination' => 'KEMRI Headquarters',
                'passengers' => 5,
                'status' => 'completed',
                'priority' => 'low',
                'approved_by' => $fleetManager->id,
                'approved_at' => Carbon::now()->subDays(3),
                'notes' => 'Training materials transported.',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            
            // Rejected booking
            [
                'vehicle_id' => $vehicleIds[1] ?? $vehicleIds[0],
                'requester_id' => $transportOfficer->id,
                'driver_id' => null,
                'start_date' => Carbon::now()->addDays(3)->setTime(10, 0),
                'end_date' => Carbon::now()->addDays(3)->setTime(15, 0),
                'purpose' => 'Personal errand - shopping for office supplies',
                'destination' => 'Nairobi CBD',
                'passengers' => 1,
                'status' => 'rejected',
                'priority' => 'low',
                'approved_by' => $fleetManager->id,
                'approved_at' => Carbon::now()->subHours(3),
                'rejection_reason' => 'Personal errands are not covered by official vehicle policy. Please use procurement process for office supplies.',
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(3),
            ],
            
            // Pending booking 3 (Low priority)
            [
                'vehicle_id' => $vehicleIds[2] ?? $vehicleIds[0],
                'requester_id' => $transportOfficer->id,
                'driver_id' => null,
                'start_date' => Carbon::now()->addDays(7)->setTime(8, 0),
                'end_date' => Carbon::now()->addDays(7)->setTime(12, 0),
                'purpose' => 'Routine inspection of health facilities in Thika',
                'destination' => 'Thika Sub-County',
                'passengers' => 2,
                'status' => 'pending',
                'priority' => 'low',
                'notes' => 'Half-day trip.',
                'created_at' => Carbon::now()->subHours(8),
                'updated_at' => Carbon::now()->subHours(8),
            ],
        ];

        foreach ($bookings as $booking) {
            DB::table('bookings')->insert($booking);
        }

        $this->command->info('✓ Bookings seeded successfully!');
        $this->command->info('  - 3 pending bookings');
        $this->command->info('  - 1 approved booking');
        $this->command->info('  - 1 completed booking');
        $this->command->info('  - 1 rejected booking');
    }
}
