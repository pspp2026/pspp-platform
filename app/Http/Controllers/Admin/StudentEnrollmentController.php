<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Student;
use App\Models\Classroom;

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

        $student->update([
            'classroom_id' => $validated['classroom_id'],
        ]);

        return redirect()
            ->route('admin.student-enrollments.index')
            ->with('success', 'จัดนักเรียนเข้าห้องเรียบร้อยแล้ว');
    }
}