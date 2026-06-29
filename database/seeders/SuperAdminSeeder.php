<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@pspp.online'],
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@pspp.online',
                'password' => Hash::make('ChangeMe@123'),
                'role' => 'superadmin',
                'status' => 'approved',
                'school_id' => null,
                'email_verified_at' => now(),
            ]
        );
    }
}