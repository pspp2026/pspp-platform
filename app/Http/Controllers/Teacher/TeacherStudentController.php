<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class TeacherStudentController extends Controller
{
    /**
     * แสดงรายชื่อนักเรียนของคาบเรียน
     */
    public function index(Schedule $schedule)
    {
        /** @var User|null $user */
        $user = Auth::user();

        abort_unless(
            $user instanceof User,
            403,
            'กรุณาเข้าสู่ระบบก่อนใช้งาน'
        );

        $teacher = $user->teacher;

    abort_unless(
    $teacher && (int) $schedule->teacher_id === (int) $teacher->user_id,
    403,
    'คุณไม่มีสิทธิ์ดูรายชื่อนักเรียนของคาบนี้'
);

        $students = Student::query()
            ->with([
                'school',
                'classroom',
                'temple',
            ])
            ->where('classroom_id', $schedule->classroom_id)
            ->orderBy('student_code')
            ->get();

        return view('teacher.students.index', compact(
            'schedule',
            'students'
        ));
    }
}