<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('TRUNCATE TABLE auth.organizations CASCADE');

        $root = Organization::create([
            'id' => (string) Str::uuid(),
            'name' => 'Ministry of Transport',
            'code' => 'MOT',
            'type' => 'ministry',
            'is_active' => true,
            'hierarchical_order' => 1,
        ]);

        Organization::create([
            'id' => (string) Str::uuid(),
            'name' => 'Department of Roads',
            'code' => 'DOR',
            'type' => 'department',
            'parent_id' => $root->id,
            'is_active' => true,
            'hierarchical_order' => 2,
        ]);

        Organization::create([
            'id' => (string) Str::uuid(),
            'name' => 'Traffic Management Unit',
            'code' => 'TMU',
            'type' => 'unit',
            'parent_id' => $root->id,
            'is_active' => true,
            'hierarchical_order' => 3,
        ]);
    }
}
