<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TempleImportController extends Controller
{
    public function import()
    {
        set_time_limit(0);

        $path = storage_path('app/Report_Temple.csv');

        if (!file_exists($path)) {
            return "❌ ไม่พบไฟล์";
        }

        $file = fopen($path, 'r');

        // อ่าน header ทิ้ง
        $header = fgetcsv($file);

        DB::beginTransaction();

        try {

            $mapping = [
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
                24 => 'province_id',
                25 => 'district_id',
                26 => 'subdistrict_id',
                27 => 'order_no',
            ];

            $batch = [];
            $success = 0;
            $skip = 0;

            while (($row = fgetcsv($file)) !== false) {

                if (!isset($row[1])) {
                    $skip++;
                    continue;
                }

                $data = [];

                foreach ($mapping as $index => $field) {
                    $value = trim($row[$index] ?? '');

                    // แปลง encoding
                    $value = mb_convert_encoding($value, 'UTF-8', ['TIS-620', 'Windows-874', 'UTF-8']);

                    // 🔥 FIX สำคัญ: ต้องมีทุก column
                    $data[$field] = $value !== '' ? $value : null;
                }

                if (empty($data['temple_code'])) {
                    $skip++;
                    continue;
                }

                // 🔥 format วันที่
                foreach (['established_date', 'visung_date', 'status_date'] as $dateField) {
                    if (!empty($data[$dateField])) {
                        try {
                            $data[$dateField] = Carbon::parse($data[$dateField])->format('Y-m-d');
                        } catch (\Exception $e) {
                            $data[$dateField] = null;
                        }
                    }
                }

                $batch[] = $data;

                if (count($batch) >= 500) {
                    $this->safeUpsert($batch);
                    $success += count($batch);
                    $batch = [];
                }
            }

            // insert ที่เหลือ
            if (!empty($batch)) {
                $this->safeUpsert($batch);
                $success += count($batch);
            }

            fclose($file);

            DB::commit();

            return "✅ Import สำเร็จ: {$success} | ข้าม: {$skip}";

        } catch (\Exception $e) {

            DB::rollBack();

            return "❌ Error: " . $e->getMessage();
        }
    }

    private function safeUpsert($batch)
    {
        // 🔥 FIX สำคัญ: ต้อง fix column ให้เท่ากันทุก row
        $allFields = [
            'temple_code',
            'temple_name',
            'temple_type',
            'sect',
            'registration_type',
            'established_date',
            'has_visung',
            'visung_date',
            'status_date',
            'subdistrict',
            'district',
            'province',
            'postal_code',
            'phone',
            'fax',
            'email',
            'website',
            'village',
            'house_no',
            'moo',
            'soi',
            'road',
            'note',
            'province_id',
            'district_id',
            'subdistrict_id',
            'order_no',
        ];

        DB::table('temples')->upsert(
            $batch,
            ['temple_code'],
            $allFields
        );
    }
}