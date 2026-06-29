<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Student;
use Illuminate\Http\Request;

class TeacherStudentController extends Controller
{
    /**
     * แสดงรายชื่อนักเรียนของคาบเรียน
     */
    public function index(Schedule $schedule)
    {
        $students = Student::query()
            ->where('classroom_id', $schedule->classroom_id)
            ->orderBy('student_code')
            ->get();

        return view('teacher.students.index', compact(
            'schedule',
            'students'
        ));
    }
}