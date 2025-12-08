<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles, permissions, and organizations first
        $this->call([
            RolePermissionSeeder::class,
            OrganizationSeeder::class,
        ]);

        // Get Ministry of Transport organization
        $motOrg = DB::table('organizations')->where('code', 'MOT')->first();
        $nairobiCounty = DB::table('organizations')->where('code', 'CNT-047')->first();

        // Create Super Admin user
        $superAdmin = User::create([
            'personal_number' => '100000',
            'name' => 'Super Administrator',
            'email' => 'superadmin@gfms.go.ke',
            'phone' => '+254700000000',
            'password' => Hash::make('password'),
            'organization_id' => $motOrg->id,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $superAdmin->assignRole('Super Admin');

        // Create Admin user
        $admin = User::create([
            'personal_number' => '123456',
            'name' => 'Admin User',
            'email' => 'admin@gfms.go.ke',
            'phone' => '+254700000001',
            'password' => Hash::make('password'),
            'organization_id' => $motOrg->id,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $admin->assignRole('Admin');

        // Create Fleet Manager user
        $fleetManager = User::create([
            'personal_number' => '234567',
            'name' => 'Jane Fleet Manager',
            'email' => 'fleet@gfms.go.ke',
            'phone' => '+254700000002',
            'password' => Hash::make('password'),
            'organization_id' => $nairobiCounty->id,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $fleetManager->assignRole('Fleet Manager');

        // Create Transport Officer user
        $transportOfficer = User::create([
            'personal_number' => '345678',
            'name' => 'John Transport Officer',
            'email' => 'transport@gfms.go.ke',
            'phone' => '+254700000003',
            'password' => Hash::make('password'),
            'organization_id' => $nairobiCounty->id,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $transportOfficer->assignRole('Transport Officer');

        // Create Driver user
        $driver = User::create([
            'personal_number' => '654321',
            'name' => 'Peter Driver',
            'email' => 'driver@gfms.go.ke',
            'phone' => '+254700000004',
            'password' => Hash::make('password'),
            'organization_id' => $nairobiCounty->id,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $driver->assignRole('Driver');

        // Create Test User with real phone number for SMS testing
        $testUser = User::create([
            'personal_number' => '999999',
            'name' => 'SMS Test User',
            'email' => 'smstest@gfms.go.ke',
            'phone' => '+254113334370',
            'password' => Hash::make('password'),
            'organization_id' => $nairobiCounty->id,
            'email_verified_at' => now(),
            'is_active' => true,
        ]);
        $testUser->assignRole('Admin');

        // Create sample vehicles
        DB::table('vehicles')->insert([
            [
                'registration_number' => 'KCA 001A',
                'make' => 'Toyota',
                'model' => 'Land Cruiser V8',
                'year' => 2023,
                'fuel_type' => 'diesel',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'registration_number' => 'KCC 201E',
                'make' => 'Toyota',
                'model' => 'Hilux',
                'year' => 2022,
                'fuel_type' => 'diesel',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create sample driver record
        DB::table('drivers')->insert([
            'user_id' => $driver->id,
            'license_number' => 'DL123456',
            'license_expiry_date' => now()->addYears(2),
            'license_class' => 'C',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('');
        $this->command->info('✓ All data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Users (Personal Number / Password):');
        $this->command->info('  Super Admin: 100000 / password');
        $this->command->info('  Admin: 123456 / password');
        $this->command->info('  Fleet Manager: 234567 / password');
        $this->command->info('  Transport Officer: 345678 / password');
        $this->command->info('  Driver: 654321 / password');
        $this->command->info('  SMS Test User: 999999 / password (Phone: +254113334370)');
    }
}
