<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProvincesTableSeeder::class,
            DistrictsTableSeeder::class,
            SubdistrictsTableSeeder::class,
            SubjectGroupSeeder::class,
            SubjectSeeder::class,
            SchoolSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}