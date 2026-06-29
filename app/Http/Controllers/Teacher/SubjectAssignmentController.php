<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubjectAssignmentController extends Controller
{
    public function index()
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'ไม่พบข้อมูลครู');
        }

        $subjects = Subject::with('group')
            ->orderBy('group_id')
            ->orderBy('subject_code')
            ->get();

        $subjects = $subjects->groupBy(function($item){

            return $item->group->name ?? 'ไม่ระบุกลุ่มสาระ';

        });

        $selectedSubjects = $teacher->subjects()
            ->pluck('subjects.id')
            ->toArray();

        return view(
            'teacher.subjects.manage',
            compact(
                'subjects',
                'selectedSubjects'
            )
        );
    }

    public function update(Request $request)
    {
        $teacher = Auth::user()->teacher;

        if (!$teacher) {
            abort(403, 'ไม่พบข้อมูลครู');
        }

        $teacher->subjects()->sync(
            $request->subjects ?? []
        );

        return back()->with(
            'success',
            'บันทึกรายวิชาที่สอนเรียบร้อยแล้ว'
        );
    }
}