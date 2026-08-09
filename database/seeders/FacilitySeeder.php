<?php

namespace Database\Seeders;

use App\Models\Facility;
use Illuminate\Database\Seeder;

class FacilitySeeder extends Seeder
{
    public function run(): void
    {
        $facilities = [
            // JHS Building
            ['building_name' => 'JHS Building',         'room_number' => 'CECD-01',  'floor' => '1st Floor', 'description' => 'CECD-01'],
            ['building_name' => 'JHS Building',         'room_number' => 'CECD-02',  'floor' => '1st Floor', 'description' => 'CECD-02'],

            // Annex Building
            ['building_name' => 'Annex Building',       'room_number' => 'ANX-03',   'floor' => '1st Floor', 'description' => 'ANX-03'],
            ['building_name' => 'Annex Building',       'room_number' => 'ANX-04',   'floor' => '1st Floor', 'description' => 'ANX-04'],
            ['building_name' => 'Annex Building',       'room_number' => 'ANX-05',   'floor' => '1st Floor', 'description' => 'ANX-05'],
            ['building_name' => 'Annex Building',       'room_number' => 'ANX-06',   'floor' => '1st Floor', 'description' => 'ANX-06'],
            ['building_name' => 'Annex Building',       'room_number' => 'ANX-07',   'floor' => '1st Floor', 'description' => 'ANX-07'],

            // Science Building - 2nd Floor
            ['building_name' => 'Science Building',     'room_number' => 'SCI-201',  'floor' => '2nd Floor', 'description' => 'SCI-201'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-202',  'floor' => '2nd Floor', 'description' => 'SCI-202'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-203',  'floor' => '2nd Floor', 'description' => 'SCI-203'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-204',  'floor' => '2nd Floor', 'description' => 'SCI-204'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-205',  'floor' => '2nd Floor', 'description' => 'SCI-205'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-206',  'floor' => '2nd Floor', 'description' => 'SCI-206'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-207',  'floor' => '2nd Floor', 'description' => 'SCI-207'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-208',  'floor' => '2nd Floor', 'description' => 'SCI-208'],

            // Science Building - 3rd Floor
            ['building_name' => 'Science Building',     'room_number' => 'SCI-301',  'floor' => '3rd Floor', 'description' => 'SCI-301'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-302',  'floor' => '3rd Floor', 'description' => 'SCI-302'],
            ['building_name' => 'Science Building',     'room_number' => 'Biology Laboratory',       'floor' => '3rd Floor', 'description' => 'Biology Laboratory'],
            ['building_name' => 'Science Building',     'room_number' => 'Microbiology Laboratory',  'floor' => '3rd Floor', 'description' => 'Microbiology & Parasitology Laboratory'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-ETLC', 'floor' => '3rd Floor', 'description' => 'SCI-ETLC'],
            ['building_name' => 'Science Building',     'room_number' => 'SCI-SW',   'floor' => '3rd Floor', 'description' => 'SCI-SW'],

            // Science Building - 4th Floor
            ['building_name' => 'Science Building',     'room_number' => 'SCI-401',  'floor' => '4th Floor', 'description' => 'SCI-401'],
            ['building_name' => 'Science Building',     'room_number' => 'JEEP Laboratory',   'floor' => '4th Floor', 'description' => 'JEEP Laboratory'],
            ['building_name' => 'Science Building',     'room_number' => 'Speech Laboratory', 'floor' => '4th Floor', 'description' => 'Speech Laboratory'],
            ['building_name' => 'Science Building',     'room_number' => 'Physics Laboratory',   'floor' => '4th Floor', 'description' => 'Physics Laboratory'],
            ['building_name' => 'Science Building',     'room_number' => 'Chemistry Laboratory', 'floor' => '4th Floor', 'description' => 'Chemistry Laboratory'],

            // TVET Building - 2nd Floor
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-201', 'floor' => '2nd Floor', 'description' => 'TVET-201'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-202', 'floor' => '2nd Floor', 'description' => 'TVET-202'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-203', 'floor' => '2nd Floor', 'description' => 'TVET-203'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-204', 'floor' => '2nd Floor', 'description' => 'TVET-204'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-205', 'floor' => '2nd Floor', 'description' => 'TVET-205'],

            // TVET Building - 3rd Floor
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-301', 'floor' => '3rd Floor', 'description' => 'TVET-301'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-302', 'floor' => '3rd Floor', 'description' => 'TVET-302'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-303', 'floor' => '3rd Floor', 'description' => 'TVET-303'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-304', 'floor' => '3rd Floor', 'description' => 'TVET-304'],

            // TVET Building - 4th Floor
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-401', 'floor' => '4th Floor', 'description' => 'TVET-401'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-402', 'floor' => '4th Floor', 'description' => 'TVET-402'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-403', 'floor' => '4th Floor', 'description' => 'TVET-403'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-404', 'floor' => '4th Floor', 'description' => 'TVET-404'],
            ['building_name' => 'TVET Building',        'room_number' => 'TVET-405', 'floor' => '4th Floor', 'description' => 'TVET-405'],

            // CBA Building - 1st Floor
            ['building_name' => 'CBA Building',         'room_number' => 'Review Room',         'floor' => '1st Floor', 'description' => 'Review Room'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-08',              'floor' => '1st Floor', 'description' => 'CBA-08'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-09',              'floor' => '1st Floor', 'description' => 'CBA-09'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-10',              'floor' => '1st Floor', 'description' => 'CBA-10'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-Incubation Room', 'floor' => '1st Floor', 'description' => 'CBA-Incubation Room'],

            // CBA Building - 2nd Floor
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-11',   'floor' => '2nd Floor', 'description' => 'CBA-11'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-12',   'floor' => '2nd Floor', 'description' => 'CBA-12'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-13',   'floor' => '2nd Floor', 'description' => 'CBA-13'],
            ['building_name' => 'CBA Building',         'room_number' => 'CBA-14',   'floor' => '2nd Floor', 'description' => 'CBA-14'],

            // HM Building - 2nd Floor
            ['building_name' => 'HM Building',          'room_number' => 'HM-15',       'floor' => '2nd Floor', 'description' => 'HM-15'],
            ['building_name' => 'HM Building',          'room_number' => 'HM-16',       'floor' => '2nd Floor', 'description' => 'HM-16'],
            ['building_name' => 'HM Building',          'room_number' => 'HM-17',       'floor' => '2nd Floor', 'description' => 'HM-17'],
            ['building_name' => 'HM Building',          'room_number' => 'HM-Mock Bar', 'floor' => '2nd Floor', 'description' => 'HM-Mock Bar'],

            // Simulated Hospital
            ['building_name' => 'Simulated Hospital',   'room_number' => 'SH-RLE',              'floor' => '1st Floor', 'description' => 'SH-RLE'],
            ['building_name' => 'Simulated Hospital',   'room_number' => 'SH-Simulated Hospital','floor' => '1st Floor', 'description' => 'SH-Simulated Hospital'],
        ];

        foreach ($facilities as $facility) {
            Facility::firstOrCreate(
                ['building_name' => $facility['building_name'], 'room_number' => $facility['room_number']],
                $facility
            );
        }
    }
}
