<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    public function run()
    {
        // ✅ path ไฟล์ (ถูกต้องแล้ว)
        $path = storage_path('app/subjects_202603261631.txt');

        // ❗ เช็คไฟล์ก่อน
        if (!file_exists($path)) {
            dd("❌ ไม่พบไฟล์: " . $path);
        }

        // ✅ อ่านไฟล์ (กัน error + ข้ามบรรทัดว่าง)
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $data = [];

        foreach ($lines as $line) {

            // 🔹 ข้าม header / เส้น ---
            if (str_contains($line, 'subject_code') || str_contains($line, '---')) {
                continue;
            }

            // 🔹 แยกข้อมูล
            $cols = array_map('trim', explode('|', $line));

            // 🔹 ตรวจจำนวน column
            if (count($cols) < 14) {
                continue;
            }

            // 🔹 map ค่า
            $subject_code = $cols[2] ?? null;
            $subject_name = $cols[3] ?? null;
            $group_id     = $cols[6] ?? null;
            $level        = $cols[7] ?? null;
            $semester     = $cols[10] ?? null;
            $year         = $cols[11] ?? null;
            $subject_type = $cols[13] ?? null;

            // 🔹 ข้ามข้อมูลไม่ครบ
            if (!$subject_code || !$subject_name) {
                continue;
            }

            $data[] = [
                'subject_code' => trim($subject_code),
                'subject_name' => trim($subject_name),
                'group_id'     => (int)$group_id,
                'level'        => (int)$level,
                'year'         => (int)$year,
                'semester'     => (int)$semester,
                'subject_type' => trim($subject_type),
            ];
        }

        // 🔥 กัน insert ว่าง
        if (empty($data)) {
            dd("❌ ไม่มีข้อมูลสำหรับ insert");
        }

        // 🔥 ล้างข้อมูลก่อน (กันซ้ำ)
        DB::table('subjects')->truncate();

        // 🔥 insert ทีเดียว
        DB::table('subjects')->insert($data);

        echo "✅ Insert สำเร็จ " . count($data) . " รายการ\n";
    }
}