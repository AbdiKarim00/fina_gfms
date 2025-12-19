<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE auth.users CASCADE');

        $org = Organization::first();

        // Create Admin User
        $admin = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => '100000',
            'name' => 'System Administrator',
            'email' => 'admin@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Create Cabinet Secretary User
        $cabinetSecretary = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => '10001',
            'name' => 'Cabinet Secretary',
            'email' => 'cabinet.secretary@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $cabinetSecretary->assignRole('cabinet_secretary');

        // Create Manager User
        $manager = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => '100002',
            'name' => 'Fleet Manager',
            'email' => 'manager@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('fleet_manager');

        // Create Driver User
        $driver = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => '100003',
            'name' => 'John Doe',
            'email' => 'driver@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $driver->assignRole('authorized_driver');
    }
}
