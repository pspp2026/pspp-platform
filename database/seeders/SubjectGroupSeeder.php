<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectGroupSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('subject_groups')->truncate(); // 🔥 ล้างก่อน

        DB::table('subjectgroups')->insert([
            ['id' => 1, 'name' => 'ภาษาไทย'],
            ['id' => 2, 'name' => 'คณิตศาสตร์'],
            ['id' => 3, 'name' => 'วิทยาศาสตร์'],
            ['id' => 4, 'name' => 'สังคมศึกษา'],
            ['id' => 5, 'name' => 'สุขศึกษา'],
            ['id' => 6, 'name' => 'ศิลปะ'],
            ['id' => 7, 'name' => 'การงานอาชีพ'],
            ['id' => 8, 'name' => 'ภาษาอังกฤษ'],
            ['id' => 9, 'name' => 'ภาษาบาลี'],
        ]);

        echo "✅ Subject Groups inserted\n";
    }
}