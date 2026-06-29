<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Enrollment;
use App\Models\Student;
use App\Models\School;
use App\Models\Classroom;
use App\Models\AcademicTerm;

class EnrollmentController extends Controller
{
    /**
     * แสดงรายการการลงทะเบียนเรียน
     */
    public function index()
    {
        
    // ทดสอบ Policy
        $this->authorize('viewAny', Enrollment::class);

        $enrollments = Enrollment::with([
            'student.user',
            'school',
            'classroom',
            'academicTerm'
        ])
        ->latest()
        ->paginate(20);

        return view(
            'admin.enrollments.index',
            compact('enrollments')
        );
    

        $enrollments = Enrollment::with([
            'student.user',
            'school',
            'classroom',
            'academicTerm'
        ])
        ->latest()
        ->paginate(20);

        return view(
            'admin.enrollments.index',
            compact('enrollments')
        );
    }

    /**
     * ฟอร์มเพิ่มการลงทะเบียน
     */
    public function create()
    {
        return view('admin.enrollments.create', [
            'students' => Student::with('user')
                ->orderBy('student_code')
                ->get(),

            'schools' => School::orderBy('school_name')->get(),

            'classrooms' => Classroom::orderBy('name')->get(),

            'terms' => AcademicTerm::orderByDesc('academic_year')
                ->orderBy('semester')
                ->get(),
        ]);
    }

    /**
     * บันทึกข้อมูล
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'status' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        $classroom = Classroom::findOrFail($request->classroom_id);

        $term = AcademicTerm::findOrFail($request->academic_term_id);

        Enrollment::create([

            'student_id' => $student->id,

            'school_id' => $student->school_id,

            'classroom_id' => $classroom->id,

            'academic_term_id' => $term->id,

            'grade_level' => $classroom->level,

            'academic_year' => $term->academic_year,

            'semester' => $term->semester,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'ลงทะเบียนเรียนเรียบร้อย');
    }

    /**
     * ฟอร์มแก้ไข
     */
    public function edit(Enrollment $enrollment)
    {
        return view('admin.enrollments.edit', [
            'enrollment' => $enrollment,

            'students' => Student::with('user')
                ->orderBy('student_code')
                ->get(),

            'schools' => School::orderBy('school_name')->get(),

            'classrooms' => Classroom::orderBy('name')->get(),

            'terms' => AcademicTerm::orderByDesc('academic_year')
                ->orderBy('semester')
                ->get(),
        ]);
    }

    /**
     * อัปเดตข้อมูล
     */
    public function update(Request $request, Enrollment $enrollment)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'academic_term_id' => 'required|exists:academic_terms,id',
            'status' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);

        $classroom = Classroom::findOrFail($request->classroom_id);

        $term = AcademicTerm::findOrFail($request->academic_term_id);

        $enrollment->update([

            'student_id' => $student->id,

            'school_id' => $student->school_id,

            'classroom_id' => $classroom->id,

            'academic_term_id' => $term->id,

            'grade_level' => $classroom->level,

            'academic_year' => $term->academic_year,

            'semester' => $term->semester,

            'status' => $request->status,

        ]);

        return redirect()
            ->route('enrollments.index')
            ->with('success', 'แก้ไขข้อมูลเรียบร้อย');
    }

    /**
     * ลบข้อมูล
     */
    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return back()->with(
            'success',
            'ลบข้อมูลเรียบร้อย'
        );
    }
    /**
     * แสดงหน้าจอ Import CSV
     */
    public function import()
    {
        return view('admin.enrollments.import');
    }

    /**
     * นำเข้าข้อมูลจาก CSV
     */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        // จะพัฒนาส่วนอ่าน CSV ในขั้นตอนถัดไป

        return redirect()
            ->route('admin.enrollments.import')
            ->with('success', 'อัปโหลดไฟล์สำเร็จ (ยังไม่ได้เริ่ม Import)');
    }

    /**
     * ดาวน์โหลด Template CSV
     */
    public function downloadTemplate()
    {
        $path = public_path('templates/student_template.csv');

        if (! file_exists($path)) {
            abort(404, 'ไม่พบไฟล์ Template');
        }

        return response()->download($path);
    }

}