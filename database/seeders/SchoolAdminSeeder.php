<?php

namespace Database\Seeders;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SchoolAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $schools = School::all();

        foreach ($schools as $school) {

            $username = strtoupper($school->short_name);

            User::updateOrCreate(
                    [
                        'email' => strtolower($school->short_name) . '@pspp.online',
                    ],
                    [
                        'name'              => strtoupper($school->short_name),

                        'email'             => strtolower($school->short_name) . '@pspp.online',

                        'password'          => Hash::make('12345678'),

                        'role'              => 'admin',
                        'status'            => 'approved',

                        'school_id'         => $school->id,

                        'email_verified_at' => now(),
                    ]
                );
        }

        $this->command->info('School Admin Seeder completed successfully.');
    }
}