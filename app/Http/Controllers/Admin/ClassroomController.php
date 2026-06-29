<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\School;
use App\Models\AcademicTerm;
use Illuminate\Http\Request;

class ClassroomController extends Controller
{
    public function index()
    {
        $classrooms = Classroom::with('school')
            ->latest()
            ->paginate(20);

        $currentTerm = AcademicTerm::where('is_active', 1)
            ->first();

        return view(
            'admin.classrooms.index',
            compact(
                'classrooms',
                'currentTerm'
            )
        );
    }

    public function create()
    {
        $schools = School::all();

        return view('admin.classrooms.create', compact('schools'));
    }

   public function store(Request $request)
    {
        $request->validate([
            'school_id'     => 'required',
            'level'         => 'required',
            'room'          => 'required|integer|min:1',
            'student_count' => 'nullable|integer'
        ]);

        $name = $request->level . '/' . $request->room;

        Classroom::create([
            'school_id'     => $request->school_id,
            'name'          => $name,
            'level'         => $request->level,
            'room'          => $request->room,
            'student_count' => $request->student_count,
        ]);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'เพิ่มห้องเรียนสำเร็จ');
    }

    public function edit(Classroom $classroom)
    {
        $schools = School::all();

        return view('admin.classrooms.edit', compact(
            'classroom',
            'schools'
        ));
    }

    public function update(Request $request, Classroom $classroom)
    {
        $request->validate([
            'school_id' => 'required',
            'name' => 'required',
        ]);

        $classroom->update($request->all());

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'แก้ไขสำเร็จ');
    }

    public function destroy(Classroom $classroom)
    {
        $classroom->delete();

        return back()->with('success', 'ลบสำเร็จ');
    }
}