<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed roles, permissions and organizations first
        $this->call([
            RolePermissionSeeder::class,
            OrganizationSeeder::class,
            UserSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✓ All data seeded successfully!');
        $this->command->info('');
        $this->command->info('Test Users (Personal Number / Password):');
        $this->command->info('  Super Admin: 100000 / password');
        $this->command->info('  Cabinet Secretary: 100001 / password');
        $this->command->info('  Principal Secretary: 100002 / password');
        $this->command->info('  Fleet Manager: 100003 / password');
        $this->command->info('  Driver: 100004 / password');
    }
}
