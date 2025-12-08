<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create National Government Ministries
        $ministries = [
            [
                'name' => 'Ministry of Transport and Infrastructure',
                'code' => 'MOT',
                'type' => 'ministry',
                'email' => 'info@transport.go.ke',
                'phone' => '+254-20-2729200',
                'address' => 'Transcom House, Ngong Road, Nairobi',
            ],
            [
                'name' => 'Ministry of Health',
                'code' => 'MOH',
                'type' => 'ministry',
                'email' => 'info@health.go.ke',
                'phone' => '+254-20-2717077',
                'address' => 'Afya House, Cathedral Road, Nairobi',
            ],
            [
                'name' => 'Ministry of Education',
                'code' => 'MOE',
                'type' => 'ministry',
                'email' => 'info@education.go.ke',
                'phone' => '+254-20-318581',
                'address' => 'Jogoo House B, Harambee Avenue, Nairobi',
            ],
            [
                'name' => 'Ministry of Interior and National Administration',
                'code' => 'MOIN',
                'type' => 'ministry',
                'email' => 'info@interior.go.ke',
                'phone' => '+254-20-2227411',
                'address' => 'Harambee House, Harambee Avenue, Nairobi',
            ],
            [
                'name' => 'Ministry of Defence',
                'code' => 'MOD',
                'type' => 'ministry',
                'email' => 'info@mod.go.ke',
                'phone' => '+254-20-2721100',
                'address' => 'Ulinzi House, Lenana Road, Nairobi',
            ],
        ];

        foreach ($ministries as $ministry) {
            Organization::create($ministry);
        }

        // Create Kenya's 47 Counties
        $counties = [
            ['name' => 'Mombasa County', 'code' => 'CNT-001', 'capital' => 'Mombasa'],
            ['name' => 'Kwale County', 'code' => 'CNT-002', 'capital' => 'Kwale'],
            ['name' => 'Kilifi County', 'code' => 'CNT-003', 'capital' => 'Kilifi'],
            ['name' => 'Tana River County', 'code' => 'CNT-004', 'capital' => 'Hola'],
            ['name' => 'Lamu County', 'code' => 'CNT-005', 'capital' => 'Lamu'],
            ['name' => 'Taita-Taveta County', 'code' => 'CNT-006', 'capital' => 'Voi'],
            ['name' => 'Garissa County', 'code' => 'CNT-007', 'capital' => 'Garissa'],
            ['name' => 'Wajir County', 'code' => 'CNT-008', 'capital' => 'Wajir'],
            ['name' => 'Mandera County', 'code' => 'CNT-009', 'capital' => 'Mandera'],
            ['name' => 'Marsabit County', 'code' => 'CNT-010', 'capital' => 'Marsabit'],
            ['name' => 'Isiolo County', 'code' => 'CNT-011', 'capital' => 'Isiolo'],
            ['name' => 'Meru County', 'code' => 'CNT-012', 'capital' => 'Meru'],
            ['name' => 'Tharaka-Nithi County', 'code' => 'CNT-013', 'capital' => 'Chuka'],
            ['name' => 'Embu County', 'code' => 'CNT-014', 'capital' => 'Embu'],
            ['name' => 'Kitui County', 'code' => 'CNT-015', 'capital' => 'Kitui'],
            ['name' => 'Machakos County', 'code' => 'CNT-016', 'capital' => 'Machakos'],
            ['name' => 'Makueni County', 'code' => 'CNT-017', 'capital' => 'Wote'],
            ['name' => 'Nyandarua County', 'code' => 'CNT-018', 'capital' => 'Ol Kalou'],
            ['name' => 'Nyeri County', 'code' => 'CNT-019', 'capital' => 'Nyeri'],
            ['name' => 'Kirinyaga County', 'code' => 'CNT-020', 'capital' => 'Kerugoya'],
            ['name' => 'Murang\'a County', 'code' => 'CNT-021', 'capital' => 'Murang\'a'],
            ['name' => 'Kiambu County', 'code' => 'CNT-022', 'capital' => 'Kiambu'],
            ['name' => 'Turkana County', 'code' => 'CNT-023', 'capital' => 'Lodwar'],
            ['name' => 'West Pokot County', 'code' => 'CNT-024', 'capital' => 'Kapenguria'],
            ['name' => 'Samburu County', 'code' => 'CNT-025', 'capital' => 'Maralal'],
            ['name' => 'Trans-Nzoia County', 'code' => 'CNT-026', 'capital' => 'Kitale'],
            ['name' => 'Uasin Gishu County', 'code' => 'CNT-027', 'capital' => 'Eldoret'],
            ['name' => 'Elgeyo-Marakwet County', 'code' => 'CNT-028', 'capital' => 'Iten'],
            ['name' => 'Nandi County', 'code' => 'CNT-029', 'capital' => 'Kapsabet'],
            ['name' => 'Baringo County', 'code' => 'CNT-030', 'capital' => 'Kabarnet'],
            ['name' => 'Laikipia County', 'code' => 'CNT-031', 'capital' => 'Rumuruti'],
            ['name' => 'Nakuru County', 'code' => 'CNT-032', 'capital' => 'Nakuru'],
            ['name' => 'Narok County', 'code' => 'CNT-033', 'capital' => 'Narok'],
            ['name' => 'Kajiado County', 'code' => 'CNT-034', 'capital' => 'Kajiado'],
            ['name' => 'Kericho County', 'code' => 'CNT-035', 'capital' => 'Kericho'],
            ['name' => 'Bomet County', 'code' => 'CNT-036', 'capital' => 'Bomet'],
            ['name' => 'Kakamega County', 'code' => 'CNT-037', 'capital' => 'Kakamega'],
            ['name' => 'Vihiga County', 'code' => 'CNT-038', 'capital' => 'Vihiga'],
            ['name' => 'Bungoma County', 'code' => 'CNT-039', 'capital' => 'Bungoma'],
            ['name' => 'Busia County', 'code' => 'CNT-040', 'capital' => 'Busia'],
            ['name' => 'Siaya County', 'code' => 'CNT-041', 'capital' => 'Siaya'],
            ['name' => 'Kisumu County', 'code' => 'CNT-042', 'capital' => 'Kisumu'],
            ['name' => 'Homa Bay County', 'code' => 'CNT-043', 'capital' => 'Homa Bay'],
            ['name' => 'Migori County', 'code' => 'CNT-044', 'capital' => 'Migori'],
            ['name' => 'Kisii County', 'code' => 'CNT-045', 'capital' => 'Kisii'],
            ['name' => 'Nyamira County', 'code' => 'CNT-046', 'capital' => 'Nyamira'],
            ['name' => 'Nairobi City County', 'code' => 'CNT-047', 'capital' => 'Nairobi'],
        ];

        foreach ($counties as $county) {
            Organization::create([
                'name' => $county['name'],
                'code' => $county['code'],
                'type' => 'county',
                'email' => strtolower(str_replace([' ', '\'', '-'], '', $county['name'])) . '@county.go.ke',
                'phone' => '+254-700-' . str_pad($county['code'], 6, '0', STR_PAD_LEFT),
                'address' => $county['capital'] . ', Kenya',
            ]);
        }

        $this->command->info('✓ Organizations seeded successfully!');
        $this->command->info('  - 5 ministries created');
        $this->command->info('  - 47 counties created');
    }
}
