<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class TempleImportController extends Controller
{
    public function import()
    {
        set_time_limit(0); // 🔥 สำคัญมาก

        $path = storage_path('app/Report_Temple.csv');

        if (!file_exists($path)) {
            return "❌ ไม่พบไฟล์";
        }

        $rows = array_map('str_getcsv', file($path));

        $mapping = [
            0 => 'order_no',
            1 => 'temple_code',
            2 => 'temple_name',
            3 => 'temple_type',
            4 => 'sect',
            5 => 'registration_type',
            6 => 'established_date',
            7 => 'has_visung',
            8 => 'visung_date',
            9 => 'status_date',
            10 => 'subdistrict',
            11 => 'district',
            12 => 'province',
            13 => 'postal_code',
            14 => 'phone',
            15 => 'fax',
            16 => 'email',
            17 => 'website',
            18 => 'village',
            19 => 'house_no',
            20 => 'moo',
            21 => 'soi',
            22 => 'road',
            23 => 'note',
        ];

        $batch = [];
        $success = 0;
        $skip = 0;

        foreach ($rows as $i => $row) {

            if ($i == 0) continue;

            if (!isset($row[0]) || !is_numeric($row[0])) {
                $skip++;
                continue;
            }

            $data = [];

            foreach ($mapping as $index => $field) {
                $data[$field] = trim($row[$index] ?? '');
            }

            if (empty($data['temple_code'])) {
                $skip++;
                continue;
            }

            $batch[] = $data;

            // 🔥 insert ทีละ 500 แถว
            if (count($batch) >= 500) {
                DB::table('temples')->upsert(
                    $batch,
                    ['temple_code'], // unique key
                    array_keys($data)
                );

                $success += count($batch);
                $batch = [];
            }
        }

        // 🔥 insert ที่เหลือ
        if (!empty($batch)) {
            DB::table('temples')->upsert(
                $batch,
                ['temple_code'],
                array_keys($batch[0])
            );

            $success += count($batch);
        }

        return "✅ Import สำเร็จ: {$success} | ข้าม: {$skip}";
    }
}