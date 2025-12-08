<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $motOrgId = DB::table('organizations')->where('code', 'MOT')->value('id');
        $mohOrgId = DB::table('organizations')->where('code', 'MOH')->value('id');
        $ncgOrgId = DB::table('organizations')->where('code', 'NCG')->value('id');

        // Create users
        $superAdminId = DB::table('users')->insertGetId([
            'organization_id' => $motOrgId,
            'first_name' => 'System',
            'last_name' => 'Administrator',
            'email' => 'admin@gfms.go.ke',
            'phone' => '+254700000000',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $adminId = DB::table('users')->insertGetId([
            'organization_id' => $motOrgId,
            'first_name' => 'John',
            'last_name' => 'Kamau',
            'email' => 'john.kamau@transport.go.ke',
            'phone' => '+254700000001',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $fleetManagerId = DB::table('users')->insertGetId([
            'organization_id' => $mohOrgId,
            'first_name' => 'Mary',
            'last_name' => 'Wanjiku',
            'email' => 'mary.wanjiku@health.go.ke',
            'phone' => '+254700000002',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $driverId = DB::table('users')->insertGetId([
            'organization_id' => $ncgOrgId,
            'first_name' => 'Peter',
            'last_name' => 'Omondi',
            'email' => 'peter.omondi@nairobi.go.ke',
            'phone' => '+254700000003',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $userId = DB::table('users')->insertGetId([
            'organization_id' => $ncgOrgId,
            'first_name' => 'Jane',
            'last_name' => 'Akinyi',
            'email' => 'jane.akinyi@nairobi.go.ke',
            'phone' => '+254700000004',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign roles
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => $superAdminId],
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => $adminId],
            ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => $fleetManagerId],
            ['role_id' => 4, 'model_type' => 'App\\Models\\User', 'model_id' => $driverId],
            ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => $userId],
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Login credentials:');
        $this->command->info('Super Admin: admin@gfms.go.ke / password');
        $this->command->info('Admin: john.kamau@transport.go.ke / password');
        $this->command->info('Fleet Manager: mary.wanjiku@health.go.ke / password');
        $this->command->info('Driver: peter.omondi@nairobi.go.ke / password');
        $this->command->info('User: jane.akinyi@nairobi.go.ke / password');
    }
}
