<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class SubdistrictsTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::unprepared(
            File::get(database_path('data/subdistricts.sql'))
        );
    }
}
