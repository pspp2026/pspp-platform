<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\School;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔥 ตัวอย่าง: ตั้งค่าเขต 6 ให้ทุกโรงเรียนที่ยังเป็น NULL
        School::whereNull('zone_code')->update([
            'zone_code' => 6
        ]);

        // ============================
        // 🧠 ถ้าคุณอยาก map ตามจังหวัด (ขั้นสูง)
        // ============================
        /*
        $zoneMapping = [
            6 => [54, 55, 56], // ตัวอย่าง province_id ของเขต 6
            1 => [10, 11],
            2 => [20, 21],
        ];

        foreach ($zoneMapping as $zone => $provinces) {
            School::whereIn('province_id', $provinces)
                ->update(['zone_code' => $zone]);
        }
        */
    }
}