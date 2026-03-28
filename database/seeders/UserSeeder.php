<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::insert([
            [
                'name' => 'Admin User',
                'email' => 'admin@test.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
                'status' => 'approved',
            ],
            [
                'name' => 'Director User',
                'email' => 'director@test.com',
                'password' => Hash::make('123456'),
                'role' => 'director',
                'status' => 'approved',
            ],
            [
                'name' => 'Teacher User',
                'email' => 'teacher@test.com',
                'password' => Hash::make('123456'),
                'role' => 'teacher',
                'status' => 'approved',
            ],
            [
                'name' => 'Student User',
                'email' => 'student@test.com',
                'password' => Hash::make('123456'),
                'role' => 'student',
                'status' => 'approved',
            ],
            [
                'name' => 'Staff User',
                'email' => 'staff@test.com',
                'password' => Hash::make('123456'),
                'role' => 'staff',
                'status' => 'approved',
            ],
        ]);
    }
}