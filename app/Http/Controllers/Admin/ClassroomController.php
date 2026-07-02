<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Classroom;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    /**
     * รายชื่อห้องเรียนของโรงเรียนที่ Admin กำลังดูแล
     */
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $classrooms = Classroom::where('school_id', $schoolId)
            ->latest()
            ->paginate(20);

        $currentTerm = AcademicTerm::where('is_active', 1)->first();

        return view(
            'admin.classrooms.index',
            compact('classrooms', 'currentTerm')
        );
    }

    /**
     * ฟอร์มเพิ่มห้องเรียน
     */
    public function create()
    {
        return view('admin.classrooms.create');
    }

    /**
     * บันทึกห้องเรียนใหม่
     */
    public function store(Request $request)
    {
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'level' => ['required', 'string', 'max:255'],
            'room' => ['required', 'integer', 'min:1'],
            'student_count' => ['nullable', 'integer', 'min:0'],
        ]);

        $name = $validated['level'] . '/' . $validated['room'];

        $alreadyExists = Classroom::where('school_id', $schoolId)
            ->where('name', $name)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'room' => 'มีห้องเรียน ' . $name . ' อยู่ในโรงเรียนนี้แล้ว',
                ]);
        }

        Classroom::create([
            'school_id' => $schoolId,
            'name' => $name,
            'level' => $validated['level'],
            'room' => $validated['room'],
            'student_count' => $validated['student_count'] ?? 0,
        ]);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'เพิ่มห้องเรียนสำเร็จ');
    }

    /**
     * ฟอร์มแก้ไขห้องเรียน
     */
    public function edit(Classroom $classroom)
    {
        $schoolId = auth()->user()->school_id;

        abort_if($classroom->school_id != $schoolId, 403);

        return view('admin.classrooms.edit', compact('classroom'));
    }

    /**
     * บันทึกการแก้ไขห้องเรียน
     */
    public function update(Request $request, Classroom $classroom)
    {
        $schoolId = auth()->user()->school_id;

        abort_if($classroom->school_id != $schoolId, 403);

        $validated = $request->validate([
            'level' => ['required', 'string', 'max:255'],
            'room' => ['required', 'integer', 'min:1'],
            'student_count' => ['nullable', 'integer', 'min:0'],
            'status' => ['nullable', 'boolean'],
        ]);

        $name = $validated['level'] . '/' . $validated['room'];

        $alreadyExists = Classroom::where('school_id', $schoolId)
            ->where('name', $name)
            ->where('id', '!=', $classroom->id)
            ->exists();

        if ($alreadyExists) {
            return back()
                ->withInput()
                ->withErrors([
                    'room' => 'มีห้องเรียน ' . $name . ' อยู่ในโรงเรียนนี้แล้ว',
                ]);
        }

        $classroom->update([
            'name' => $name,
            'level' => $validated['level'],
            'room' => $validated['room'],
            'student_count' => $validated['student_count'] ?? 0,
            'status' => $request->boolean('status', true),
        ]);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'แก้ไขห้องเรียนสำเร็จ');
    }

    /**
     * ลบห้องเรียน
     */
    public function destroy(Classroom $classroom)
    {
        $schoolId = auth()->user()->school_id;

        abort_if($classroom->school_id != $schoolId, 403);

        $classroom->delete();

        return back()->with('success', 'ลบห้องเรียนสำเร็จ');
    }
}