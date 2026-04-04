<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Student;
use App\Models\Temple;
use App\Models\Enrollment;

class ImportStudents extends Command
{
    protected $signature = 'import:students';
    protected $description = 'Import students CSV (users + students + enrollments)';

    public function handle()
    {
        $path = storage_path('app/students.csv');

        if (!file_exists($path)) {
            $this->error('❌ ไม่พบไฟล์ students.csv');
            return;
        }

        DB::beginTransaction();

        try {

            $file = fopen($path, 'r');
            $header = fgetcsv($file); // skip header

            $count = 0;

            while (($row = fgetcsv($file)) !== false) {

                // 🔥 กันคอลัมน์ไม่ครบ
                if (count($row) < 13) {
                    $this->warn('⚠️ ข้าม row: ' . json_encode($row));
                    continue;
                }

                [
                    $student_code,
                    $prefix,
                    $first_name,
                    $last_name,
                    $id_card,
                    $birth_date,
                    $nationality,
                    $ethnicity,
                    $temple_name,
                    $school_id,
                    $grade_level,
                    $semester,
                    $academic_year
                ] = $row;

                // 🏯 temple
                $temple = Temple::firstOrCreate([
                    'temple_name' => trim($temple_name)
                ]);

                // 👤 user
                $user = User::firstOrCreate(
                    ['email' => $student_code . '@student.local'],
                    [
                        'name' => $first_name . ' ' . $last_name,
                        'password' => Hash::make(substr($student_code, -6)),
                        'role' => 'student',
                    ]
                );

                // 🎓 student
                $student = Student::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'school_id' => $school_id,
                        'student_code' => $student_code,
                        'prefix' => $prefix,
                        'first_name' => $first_name,
                        'last_name' => $last_name,
                        'id_card' => $id_card,
                        'birth_date' => $this->convertThaiDate($birth_date), // 🔥 แก้ตรงนี้
                        'nationality' => $nationality,
                        'ethnicity' => $ethnicity,
                        'temple_id' => $temple->id,
                    ]
                );

                // 📚 enrollment
                Enrollment::updateOrCreate(
                    [
                        'student_id' => $student->id,
                        'semester' => $semester,
                        'academic_year' => $academic_year,
                    ],
                    [
                        'school_id' => $school_id,
                        'grade_level' => $grade_level,
                    ]
                );

                $count++;

                if ($count % 100 == 0) {
                    $this->info("นำเข้าแล้ว: $count คน");
                }
            }

            fclose($file);

            DB::commit();

            $this->info("✅ สำเร็จทั้งหมด: $count คน");

        } catch (\Exception $e) {

            DB::rollBack();

            $this->error("❌ ERROR: " . $e->getMessage());
        }
    }

    // 🔥 แปลงวันที่ไทย → YYYY-MM-DD
    private function convertThaiDate($date)
    {
        if (!$date) return null;

        $months = [
            'ม.ค.' => '01',
            'ก.พ.' => '02',
            'มี.ค.' => '03',
            'เม.ย.' => '04',
            'พ.ค.' => '05',
            'มิ.ย.' => '06',
            'ก.ค.' => '07',
            'ส.ค.' => '08',
            'ก.ย.' => '09',
            'ต.ค.' => '10',
            'พ.ย.' => '11',
            'ธ.ค.' => '12',
        ];

        try {
            [$day, $month, $year] = explode('-', $date);

            $day = str_pad($day, 2, '0', STR_PAD_LEFT);
            $month = $months[$month] ?? '01';

            $year = (int)$year;
            $year = $year > 2500 ? $year - 543 : 2000 + $year;

            return "$year-$month-$day";

        } catch (\Exception $e) {
            return null;
        }
    }
}