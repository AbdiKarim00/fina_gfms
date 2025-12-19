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
        
        if (!$ministry || !$county) {
            $this->command->error('Ministry or County organization not found. Please run OrganizationSeeder first.');
            return;
        }

        // Define users to seed
        $users = [
            [
                'personal_number' => '100000',
                'name' => 'Super Administrator',
                'email' => 'superadmin@gfms.go.ke',
                'phone' => '+254700000000',
                'password' => Hash::make('password'),
                'organization_id' => $ministry->id,
                'job_group' => 'Administrative',
                'position' => 'System Administrator',
                'hierarchical_level' => 1,
                'is_active' => true,
                'email_verified_at' => now(),
                'role' => 'Super Admin',
            ],
            [
                'personal_number' => '100001',
                'name' => 'Hon. Alice Wambui',
                'email' => 'cabinet.secretary@gfms.go.ke',
                'phone' => '+254700000001',
                'password' => Hash::make('password'),
                'organization_id' => $ministry->id,
                'job_group' => 'Cabinet Secretary',
                'position' => 'Cabinet Secretary',
                'hierarchical_level' => 1,
                'is_active' => true,
                'email_verified_at' => now(),
                'role' => 'Cabinet Secretary',
            ],
            [
                'personal_number' => '100002',
                'name' => 'Dr. John Kamau',
                'email' => 'principal.secretary@gfms.go.ke',
                'phone' => '+254700000002',
                'password' => Hash::make('password'),
                'organization_id' => $ministry->id,
                'job_group' => 'Principal Secretary',
                'position' => 'Principal Secretary',
                'hierarchical_level' => 2,
                'is_active' => true,
                'email_verified_at' => now(),
                'role' => 'Principal Secretary',
            ],
            [
                'personal_number' => '100003',
                'name' => 'Peter Mwangi',
                'email' => 'fleet.manager@gfms.go.ke',
                'phone' => '+254700000003',
                'password' => Hash::make('password'),
                'organization_id' => $county->id,
                'job_group' => 'Fleet Management',
                'position' => 'Fleet Manager',
                'hierarchical_level' => 3,
                'is_active' => true,
                'email_verified_at' => now(),
                'role' => 'Fleet Manager',
            ],
            [
                'personal_number' => '100004',
                'name' => 'David Ochieng',
                'email' => 'driver@gfms.go.ke',
                'phone' => '+254700000004',
                'password' => Hash::make('password'),
                'organization_id' => $county->id,
                'job_group' => 'Driver',
                'position' => 'Authorized Driver',
                'hierarchical_level' => 4,
                'is_active' => true,
                'email_verified_at' => now(),
                'role' => 'Authorized Driver',
            ],
        ];

        foreach ($users as $userData) {
            $role = $userData['role'];
            unset($userData['role']);

            // Find existing user by personal_number to keep its UUID if it exists
            $existingUser = User::where('personal_number', $userData['personal_number'])->first();
            $userId = $existingUser ? $existingUser->id : (string) Str::uuid();

            $user = User::updateOrCreate(
                ['personal_number' => $userData['personal_number']],
                array_merge($userData, ['id' => $userId])
            );

            // Sync roles - clear existing and assign new
            DB::table('permissions.model_has_roles')
                ->where('model_id', $user->id)
                ->where('model_type', User::class)
                ->delete();
                
            $user->assignRole($role);
        }
        
        $this->command->info('✓ Users seeded successfully!');
        $this->command->info('  - ' . count($users) . ' users created and roles assigned');
    }
}
