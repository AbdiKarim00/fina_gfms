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
            'type' => 'MINISTRY',
        ]);

        Organization::create([
            'id' => (string) Str::uuid(),
            'name' => 'Department of Roads',
            'code' => 'DOR',
            'type' => 'DEPARTMENT',
            'parent_id' => $root->id,
        ]);

        Organization::create([
            'id' => (string) Str::uuid(),
            'name' => 'Traffic Management Unit',
            'code' => 'TMU',
            'type' => 'UNIT',
            'parent_id' => $root->id,
        ]);
    }
}
