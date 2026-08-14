<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $students = Student::query()
        ->when($user->role !== 'superadmin', function ($query) use ($user) {
            $query->where('school_id', $user->school_id);
                })
                ->with([
                    'school:id,school_name',
                    'classroom:id,name',
                    'user:id,name,email',
                ])
                
            ->when(
                $user->role === 'superadmin' && $request->filled('school_id'),
                function ($query) use ($request) {
                    $query->where('school_id', $request->school_id);
                })
          
            ->when($request->filled('classroom_id'), function ($query) use ($request) {
                $query->where('classroom_id', $request->classroom_id);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);

                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('student_code', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('id_card', 'like', "%{$search}%");
                });
            })
            ->orderBy('school_id')
            ->orderBy('classroom_id')
            ->orderBy('student_code')
            ->paginate(20)
            ->withQueryString();

       $schools = School::query()
        ->select('id', 'school_name')
        ->when($user->role !== 'superadmin', function ($query) use ($user) {
            $query->where('id', $user->school_id);
        })
        ->orderBy('school_name')
        ->get();

        $classrooms = Classroom::query()
        ->when($user->role !== 'superadmin', function ($query) use ($user) {
            $query->where('school_id', $user->school_id);
        })
        ->orderBy('name')
        ->get();

        return view('admin.students.index', compact(
            'students',
            'schools',
            'classrooms'
        ));
    }

    public function create()
    {
        
    $user = Auth::user();

    $schools = School::query()
        ->select('id', 'school_name')
        ->when($user->role !== 'superadmin', function ($query) use ($user) {
            $query->where('id', $user->school_id);
        })
        ->orderBy('school_name')
        ->get();

    $classrooms = Classroom::query()
        ->select('id', 'school_id', 'name')
        ->when($user->role !== 'superadmin', function ($query) use ($user) {
            $query->where('school_id', $user->school_id);
        })
        ->orderBy('name')
        ->get();

        return view('admin.students.create', compact(
            'schools',
            'classrooms'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $data = $this->validateStudent($request);

        if ($user->role !== 'superadmin') {
            $data['school_id'] = $user->school_id;
        }

        $data['user_id'] = null;
        $data['temple_id'] = null;

        Student::create($data);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'เพิ่มรายชื่อนักเรียนเรียบร้อยแล้ว');
    }

    public function edit(Student $student)
    {
        $user = Auth::user();

        if ($user->role !== 'superadmin' && $student->school_id != $user->school_id) {
            abort(403);
        }        
        $schools = School::query()
            ->select('id', 'school_name')
            ->when($user->role !== 'superadmin', function ($query) use ($user) {
                $query->where('id', $user->school_id);
            })
            ->orderBy('school_name')
            ->get();

        $classrooms = Classroom::query()
            ->select('id', 'school_id', 'name')
            ->when($user->role !== 'superadmin', function ($query) use ($user) {
                $query->where('school_id', $user->school_id);
            })
            ->orderBy('name')
            ->get();

        return view('admin.students.edit', compact(
            'student',
            'schools',
            'classrooms'
        ));
    }

    public function update(Request $request, Student $student)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $student->school_id != $user->school_id) {
            abort(403);
        }

        $data = $this->validateStudent($request, $student);
        if ($user->role !== 'superadmin') {
            $data['school_id'] = $user->school_id;
        }

        $data['temple_id'] = null;

        $student->update($data);

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'แก้ไขข้อมูลนักเรียนเรียบร้อยแล้ว');
    }

    public function destroy(Student $student)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $student->school_id != $user->school_id) {
            abort(403);
        }

        if ($student->user_id) {
            return back()->with(
                'error',
                'ไม่สามารถลบรายชื่อนี้ได้ เพราะเชื่อมกับบัญชีผู้ใช้แล้ว'
            );
        }

        $student->delete();

        return redirect()
            ->route('admin.students.index')
            ->with('success', 'ลบรายชื่อนักเรียนเรียบร้อยแล้ว');
    }

    private function validateStudent(Request $request, ?Student $student = null): array
    {
        $studentId = $student?->id;

        return $request->validate([
            'school_id' => ['required', 'exists:schools,id'],
            'classroom_id' => ['nullable', 'exists:classrooms,id'],

            'student_code' => [
                'required',
                'string',
                'max:255',
                'unique:students,student_code,' . $studentId,
            ],

            'prefix' => ['nullable', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'id_card' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'nationality' => ['nullable', 'string', 'max:255'],
            'ethnicity' => ['nullable', 'string', 'max:255'],
        ]);
    }
}