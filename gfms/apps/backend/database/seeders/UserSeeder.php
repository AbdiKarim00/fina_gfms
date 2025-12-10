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

        // Create users with personal numbers
        $superAdminId = DB::table('users')->insertGetId([
            'personal_number' => '111111',
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
            'personal_number' => '222222',
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
            'personal_number' => '123456',
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

        $transportOfficerId = DB::table('users')->insertGetId([
            'personal_number' => '444444',
            'organization_id' => $motOrgId,
            'first_name' => 'Sarah',
            'last_name' => 'Njeri',
            'email' => 'sarah.njeri@transport.go.ke',
            'phone' => '+254700000003',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $driverId = DB::table('users')->insertGetId([
            'personal_number' => '555555',
            'organization_id' => $ncgOrgId,
            'first_name' => 'Peter',
            'last_name' => 'Omondi',
            'email' => 'peter.omondi@nairobi.go.ke',
            'phone' => '+254700000004',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign roles (role_id: 1=Super Admin, 2=Admin, 3=Fleet Manager, 4=Transport Officer, 5=Driver)
        DB::table('model_has_roles')->insert([
            ['role_id' => 1, 'model_type' => 'App\\Models\\User', 'model_id' => $superAdminId],
            ['role_id' => 2, 'model_type' => 'App\\Models\\User', 'model_id' => $adminId],
            ['role_id' => 3, 'model_type' => 'App\\Models\\User', 'model_id' => $fleetManagerId],
            ['role_id' => 4, 'model_type' => 'App\\Models\\User', 'model_id' => $transportOfficerId],
            ['role_id' => 5, 'model_type' => 'App\\Models\\User', 'model_id' => $driverId],
        ]);

        $this->command->info('Users seeded successfully!');
        $this->command->info('Login credentials (use Personal Number):');
        $this->command->info('Super Admin: 111111 / password (System Administrator)');
        $this->command->info('Admin: 222222 / password (John Kamau)');
        $this->command->info('Fleet Manager: 123456 / password (Mary Wanjiku)');
        $this->command->info('Transport Officer: 444444 / password (Sarah Njeri)');
        $this->command->info('Driver: 555555 / password (Peter Omondi)');
    }
}
