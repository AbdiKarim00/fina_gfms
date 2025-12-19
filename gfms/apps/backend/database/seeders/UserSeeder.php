<?php

namespace Database\Seeders;

use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get organizations
        $ministry = Organization::where('type', 'ministry')->first();
        $county = Organization::where('type', 'county')->first();
        
        // Create super admin user
        $superAdmin = User::create([
            'id' => Str::uuid(),
            'personal_number' => '100000',
            'name' => 'Super Administrator',
            'email' => 'superadmin@gfms.go.ke',
            'phone' => '+254700000000',
            'password' => Hash::make('password'),
            'organization_id' => $ministry->id ?? null,
            'job_group' => 'Administrative',
            'position' => 'System Administrator',
            'hierarchical_level' => 1,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Assign Super Admin role
        $superAdmin->assignRole('Super Admin');
        
        // Create Cabinet Secretary user
        $cabinetSecretary = User::create([
            'id' => Str::uuid(),
            'personal_number' => '100001',
            'name' => 'Hon. Alice Wambui',
            'email' => 'cabinet.secretary@gfms.go.ke',
            'phone' => '+254700000001',
            'password' => Hash::make('password'),
            'organization_id' => $ministry->id ?? null,
            'job_group' => 'Cabinet Secretary',
            'position' => 'Cabinet Secretary',
            'hierarchical_level' => 1,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Assign Cabinet Secretary role
        $cabinetSecretary->assignRole('Cabinet Secretary');
        
        // Create Principal Secretary user
        $principalSecretary = User::create([
            'id' => Str::uuid(),
            'personal_number' => '100002',
            'name' => 'Dr. John Kamau',
            'email' => 'principal.secretary@gfms.go.ke',
            'phone' => '+254700000002',
            'password' => Hash::make('password'),
            'organization_id' => $ministry->id ?? null,
            'job_group' => 'Principal Secretary',
            'position' => 'Principal Secretary',
            'hierarchical_level' => 2,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Assign Principal Secretary role
        $principalSecretary->assignRole('Principal Secretary');
        
        // Create Fleet Manager user
        $fleetManager = User::create([
            'id' => Str::uuid(),
            'personal_number' => '100003',
            'name' => 'Peter Mwangi',
            'email' => 'fleet.manager@gfms.go.ke',
            'phone' => '+254700000003',
            'password' => Hash::make('password'),
            'organization_id' => $county->id ?? null,
            'job_group' => 'Fleet Management',
            'position' => 'Fleet Manager',
            'hierarchical_level' => 3,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Assign Fleet Manager role
        $fleetManager->assignRole('Fleet Manager');
        
        // Create Driver user
        $driver = User::create([
            'id' => Str::uuid(),
            'personal_number' => '100004',
            'name' => 'David Ochieng',
            'email' => 'driver@gfms.go.ke',
            'phone' => '+254700000004',
            'password' => Hash::make('password'),
            'organization_id' => $county->id ?? null,
            'job_group' => 'Driver',
            'position' => 'Authorized Driver',
            'hierarchical_level' => 4,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        
        // Assign Driver role
        $driver->assignRole('Authorized Driver');
        
        $this->command->info('✓ Users seeded successfully!');
        $this->command->info('  - 5 users created (Super Admin, Cabinet Secretary, Principal Secretary, Fleet Manager, Driver)');
    }
}
