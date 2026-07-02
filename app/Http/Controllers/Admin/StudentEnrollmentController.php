<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class StudentEnrollmentController extends Controller
{
    /**
     * แสดงรายชื่อนักเรียน
     */
    public function index(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $students = Student::with('classroom')
            ->where('school_id', $schoolId)
            ->orderBy('student_code')
            ->paginate(20);

        return view('admin.student-enrollments.index', compact('students'));
    }

    /**
     * แสดงหน้า Import CSV
     */
    public function importForm()
    {
        return view('admin.student-enrollments.import');
    }

    /**
     * นำเข้ารายชื่อนักเรียนจาก CSV
     *
     * รูปแบบหัวตาราง:
     * student_code,prefix,first_name,last_name,id_card,birth_date
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:5120'],
        ], [
            'file.required' => 'กรุณาเลือกไฟล์ CSV',
            'file.file' => 'ไฟล์ที่อัปโหลดไม่ถูกต้อง',
            'file.mimes' => 'รองรับเฉพาะไฟล์ CSV เท่านั้น',
            'file.max' => 'ขนาดไฟล์ต้องไม่เกิน 5 MB',
        ]);

        $schoolId = auth()->user()->school_id;
        $userId = auth()->user()->id;

        if (!$schoolId) {
            return back()->withErrors([
                'file' => 'ไม่พบข้อมูลโรงเรียนของผู้ใช้งาน',
            ]);
        }

        $uploadedFile = $request->file('file');
        $path = $uploadedFile->getRealPath();

        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->withErrors([
                'file' => 'ไม่สามารถเปิดอ่านไฟล์ CSV ได้',
            ]);
        }

        $header = fgetcsv($handle);

        if (!$header) {
            fclose($handle);

            return back()->withErrors([
                'file' => 'ไม่พบหัวตารางในไฟล์ CSV',
            ]);
        }

        // ลบ UTF-8 BOM ที่อาจติดมากับหัวคอลัมน์แรก
        $header[0] = preg_replace('/^\xEF\xBB\xBF/', '', $header[0]);

        $header = array_map(function ($value) {
            return trim(Str::lower($value));
        }, $header);

        $requiredColumns = [
            'student_code',
            'prefix',
            'first_name',
            'last_name',
            'id_card',
            'birth_date',
        ];

        $missingColumns = array_diff($requiredColumns, $header);

        if (!empty($missingColumns)) {
            fclose($handle);

            return back()->withErrors([
                'file' => 'หัวตาราง CSV ไม่ถูกต้อง ต้องมี: ' . implode(', ', $requiredColumns),
            ]);
        }

        $columnIndex = array_flip($header);

        $importedCount = 0;
        $updatedCount = 0;
        $skippedCount = 0;
        $errors = [];
        $rowNumber = 1;

        DB::beginTransaction();

        try {
            while (($row = fgetcsv($handle)) !== false) {
                $rowNumber++;

                // ข้ามแถวว่าง
                $hasValue = collect($row)->filter(function ($value) {
                    return trim((string) $value) !== '';
                })->isNotEmpty();

                if (!$hasValue) {
                    continue;
                }

                $studentCode = trim((string) ($row[$columnIndex['student_code']] ?? ''));
                $prefix = trim((string) ($row[$columnIndex['prefix']] ?? ''));
                $firstName = trim((string) ($row[$columnIndex['first_name']] ?? ''));
                $lastName = trim((string) ($row[$columnIndex['last_name']] ?? ''));
                $idCard = trim((string) ($row[$columnIndex['id_card']] ?? ''));
                $birthDate = trim((string) ($row[$columnIndex['birth_date']] ?? ''));

                if ($studentCode === '' || $firstName === '' || $lastName === '') {
                    $skippedCount++;

                    $errors[] = "แถวที่ {$rowNumber}: กรุณากรอกรหัสนักเรียน ชื่อ และนามสกุล";

                    continue;
                }

                // ถ้าไม่มีข้อมูล ให้บันทึกเป็น null
                $idCard = $idCard !== '' ? $idCard : null;
                $birthDate = $birthDate !== '' ? $birthDate : null;

                // ตรวจรูปแบบวันเกิด หากกรอกมา
                if ($birthDate !== null) {
                    $date = \DateTime::createFromFormat('Y-m-d', $birthDate);

                    if (!$date || $date->format('Y-m-d') !== $birthDate) {
                        $skippedCount++;

                        $errors[] = "แถวที่ {$rowNumber}: วันเกิดต้องเป็นรูปแบบ YYYY-MM-DD เช่น 2012-05-15";

                        continue;
                    }
                }

                $student = Student::where('school_id', $schoolId)
                    ->where('student_code', $studentCode)
                    ->first();

                $studentData = [
                    'school_id' => $schoolId,
                    'prefix' => $prefix !== '' ? $prefix : null,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'id_card' => $idCard,
                    'birth_date' => $birthDate,
                ];

                if ($student) {
                    $student->update($studentData);
                    $updatedCount++;
                } else {
                    /*
                     * ตาราง students ของระบบปัจจุบันกำหนด user_id เป็น NOT NULL
                     * จึงต้องสร้าง User ก่อน หาก Model Student ของคุณยังบังคับ user_id
                     * ให้ส่งไฟล์ User model / migration users มาเพิ่มเติม
                     */
                    Student::create(array_merge($studentData, [
                        'student_code' => $studentCode,
                        'classroom_id' => null,
                        'temple_id' => null,
                        'user_id' => $userId,
                    ]));

                    $importedCount++;
                }
            }

            fclose($handle);

            DB::commit();

            $message = "นำเข้าข้อมูลเสร็จสิ้น: เพิ่มใหม่ {$importedCount} คน, อัปเดต {$updatedCount} คน";

            if ($skippedCount > 0) {
                $message .= ", ข้าม {$skippedCount} แถว";
            }

            return redirect()
                ->route('admin.enrollments.index')
                ->with('success', $message)
                ->with('import_errors', $errors);

        } catch (\Throwable $e) {
            DB::rollBack();
            fclose($handle);

            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'file' => 'ไม่สามารถนำเข้าข้อมูลได้: ' . $e->getMessage(),
                ]);
        }
    }

    /**
     * ฟอร์มแก้ไขการจัดห้อง
     */
    public function edit(Student $student)
    {
        $schoolId = auth()->user()->school_id;

        abort_if($student->school_id != $schoolId, 403);

        $classrooms = Classroom::where('school_id', $schoolId)
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        return view(
            'admin.student-enrollments.edit',
            compact('student', 'classrooms')
        );
    }

    /**
     * บันทึกการจัดห้อง
     */
    public function update(Request $request, Student $student)
    {
        $schoolId = auth()->user()->school_id;

        abort_if($student->school_id != $schoolId, 403);

        $validated = $request->validate([
            'classroom_id' => ['nullable', 'exists:classrooms,id'],
        ]);

        if (!empty($validated['classroom_id'])) {
            $classroomBelongsToSchool = Classroom::where('id', $validated['classroom_id'])
                ->where('school_id', $schoolId)
                ->exists();

            abort_unless($classroomBelongsToSchool, 403);
        }

        $student->update([
            'classroom_id' => $validated['classroom_id'] ?? null,
        ]);

        return redirect()
            ->route('admin.student-enrollments.index')
            ->with('success', 'จัดนักเรียนเข้าห้องเรียบร้อยแล้ว');
    }
}