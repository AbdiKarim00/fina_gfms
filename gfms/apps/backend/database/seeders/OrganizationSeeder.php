<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create ministries
        $ministries = [
            [
                'name' => 'Ministry of Transport, Infrastructure, Housing, Urban Development and Public Works',
                'code' => 'MOTIHUUDPW',
                'type' => 'ministry',
                'email' => 'info@transport.go.ke',
                'phone' => '+254201234567',
                'address' => 'Transport Plaza, Nairobi',
                'level' => 1,
                'hierarchical_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Ministry of Interior and National Administration',
                'code' => 'MIONA',
                'type' => 'ministry',
                'email' => 'info@interior.go.ke',
                'phone' => '+254202345678',
                'address' => 'Nyayo House, Nairobi',
                'level' => 1,
                'hierarchical_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Ministry of Health',
                'code' => 'MOH',
                'type' => 'ministry',
                'email' => 'info@health.go.ke',
                'phone' => '+254203456789',
                'address' => 'Afya House, Nairobi',
                'level' => 1,
                'hierarchical_order' => 3,
                'is_active' => true,
            ],
        ];
        
        foreach ($ministries as $ministryData) {
            Organization::updateOrCreate(
                ['code' => $ministryData['code']],
                array_merge($ministryData, [
                    'id' => Organization::where('code', $ministryData['code'])->first()->id ?? Str::uuid(),
                ])
            );
        }
        
        // Create counties
        $counties = [
            [
                'name' => 'Nairobi City County',
                'code' => 'NCC',
                'type' => 'county',
                'email' => 'info@nairobi.go.ke',
                'phone' => '+254204567890',
                'address' => 'City Hall, Nairobi',
                'level' => 1,
                'hierarchical_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Mombasa County',
                'code' => 'MC',
                'type' => 'county',
                'email' => 'info@mombasa.go.ke',
                'phone' => '+254205678901',
                'address' => 'Mombasa County Headquarters',
                'level' => 1,
                'hierarchical_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Kisumu County',
                'code' => 'KC',
                'type' => 'county',
                'email' => 'info@kisumu.go.ke',
                'phone' => '+254206789012',
                'address' => 'Kisumu County Headquarters',
                'level' => 1,
                'hierarchical_order' => 3,
                'is_active' => true,
            ],
        ];
        
        foreach ($counties as $countyData) {
            Organization::updateOrCreate(
                ['code' => $countyData['code']],
                array_merge($countyData, [
                    'id' => Organization::where('code', $countyData['code'])->first()->id ?? Str::uuid(),
                ])
            );
        }
        
        $this->command->info('✓ Organizations seeded successfully!');
        $this->command->info('  - ' . count($ministries) . ' ministries created');
        $this->command->info('  - ' . count($counties) . ' counties created');
    }
}
