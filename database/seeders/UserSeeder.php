<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole       = Role::where('slug', 'admin')->first();
        $facultyRole     = Role::where('slug', 'faculty')->first();
        $maintenanceRole = Role::where('slug', 'maintenance')->first();

        // Admin
        User::firstOrCreate(['email' => 'admin@scc.edu.ph'], [
            'role_id'        => $adminRole->id,
            'first_name'     => 'System',
            'last_name'      => 'Administrator',
            'password'       => Hash::make('Admin@1234'),
            'department'     => 'IT Department',
            'contact_number' => '09000000001',
            'status'         => 'active',
        ]);

        // Faculty/Staff
        User::firstOrCreate(['email' => 'faculty@scc.edu.ph'], [
            'role_id'        => $facultyRole->id,
            'first_name'     => 'Maria',
            'last_name'      => 'Santos',
            'password'       => Hash::make('Faculty@1234'),
            'department'     => 'College of Education',
            'contact_number' => '09000000002',
            'status'         => 'active',
        ]);

        User::firstOrCreate(['email' => 'faculty2@scc.edu.ph'], [
            'role_id'        => $facultyRole->id,
            'first_name'     => 'Juan',
            'last_name'      => 'Dela Cruz',
            'password'       => Hash::make('Faculty@1234'),
            'department'     => 'College of Business',
            'contact_number' => '09000000003',
            'status'         => 'active',
        ]);

        // Maintenance Staff
        $maintenanceUsers = [
            ['email' => 'rcagay@scc.edu.ph',    'first_name' => 'Ricardo',    'last_name' => 'Cagay',    'contact_number' => '09000000010'],
            ['email' => 'rsucayre@scc.edu.ph',  'first_name' => 'Richard',    'last_name' => 'Sucayre',  'contact_number' => '09000000011'],
            ['email' => 'jselma@scc.edu.ph',    'first_name' => 'Janry',      'last_name' => 'Selma',    'contact_number' => '09000000012'],
            ['email' => 'eomotong@scc.edu.ph',  'first_name' => 'Edgar',      'last_name' => 'Omotong',  'contact_number' => '09000000013'],
            ['email' => 'mcaingcoy@scc.edu.ph', 'first_name' => 'Mike Raven', 'last_name' => 'Caingcoy', 'contact_number' => '09000000014'],
            ['email' => 'egudoy@scc.edu.ph',    'first_name' => 'Erwin',      'last_name' => 'Gudoy',    'contact_number' => '09000000015'],
            ['email' => 'winbing@scc.edu.ph',   'first_name' => 'Winsor',     'last_name' => 'Inbing',   'contact_number' => '09000000016'],
            ['email' => 'vbonzo@scc.edu.ph',    'first_name' => 'Vicente',    'last_name' => 'Bonzo',    'contact_number' => '09000000017'],
        ];

        foreach ($maintenanceUsers as $user) {
            User::firstOrCreate(['email' => $user['email']], [
                'role_id'        => $maintenanceRole->id,
                'first_name'     => $user['first_name'],
                'last_name'      => $user['last_name'],
                'password'       => Hash::make('Maintenance@1234'),
                'department'     => 'Facilities & Maintenance',
                'contact_number' => $user['contact_number'],
                'status'         => 'active',
            ]);
        }
    }
}
