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
            'personal_number' => 'ADM001',
            'name' => 'System Administrator',
            'email' => 'admin@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('Admin');

        // Create Cabinet Secretary User
        $cabinetSecretary = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => 'CS001',
            'name' => 'Cabinet Secretary',
            'email' => 'cabinet.secretary@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $cabinetSecretary->assignRole('Cabinet Secretary');

        // Create Manager User
        $manager = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => 'MGR001',
            'name' => 'Fleet Manager',
            'email' => 'manager@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $manager->assignRole('Manager');

        // Create Driver User
        $driver = User::create([
            'id' => (string) Str::uuid(),
            'personal_number' => 'DRV001',
            'name' => 'John Doe',
            'email' => 'driver@gfms.gov.ls',
            'password' => Hash::make('password'),
            'organization_id' => $org->id,
            'email_verified_at' => now(),
        ]);
        $driver->assignRole('Driver');
    }
}
