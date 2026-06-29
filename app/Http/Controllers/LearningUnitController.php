<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use App\Models\LearningUnit;
use App\Models\LessonPlan;
use App\Models\LessonProgress;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\School;
use App\Models\UnitProgress;

use Illuminate\Http\Request;


class LearningUnitController extends Controller
{
    // แสดงฟอร์มเพิ่มหน่วยการเรียนรู้
    public function create(Subject $subject)
    {
        return view('subjects.units.create', compact('subject'));
    }
// แสดงฟอร์มแก้ไขหน่วยการเรียนรู้
    public function edit($subject, $unit)
    {
        $unit = LearningUnit::findOrFail($unit);

        return view(
            'subjects.units.edit',
            compact('unit')
        );
    }

    // บันทึกหน่วยการเรียนรู้
    public function store(Request $request, Subject $subject)
    {
        $request->validate([
            'unit_name' => 'required|max:255',
            'hours' => 'nullable|integer'
        ]);

        LearningUnit::create([
            'subject_id' => $subject->id,
            'unit_no' => $subject->units()->count() + 1,
            'unit_name' => $request->unit_name,
            'hours' => $request->hours ?? 0,
            'description' => $request->description
        ]);

        return redirect()
            ->route('subjects.show', $subject->id)
            ->with('success', 'เพิ่มหน่วยการเรียนรู้สำเร็จ');
    }

// บันทึกการแก้ไข
public function update(Request $request, LearningUnit $unit)
{
    $request->validate([
        'unit_name' => 'required|max:255',
        'hours' => 'nullable|integer'
    ]);

    $unit->update([
        'unit_name' => $request->unit_name,
        'hours' => $request->hours ?? 0,
        'description' => $request->description
    ]);

    return redirect()
        ->route('subjects.show', $unit->subject_id)
        ->with('success', 'แก้ไขหน่วยการเรียนรู้สำเร็จ');
}

// ลบหน่วยการเรียนรู้
public function destroy(LearningUnit $unit)
{
    $subjectId = $unit->subject_id;

    $unit->delete();

    return redirect()
        ->route('subjects.show', $subjectId)
        ->with('success', 'ลบหน่วยการเรียนรู้สำเร็จ');
}

}