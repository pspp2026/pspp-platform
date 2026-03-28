<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subject;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        // รับค่า filter
        $class = $request->input('class', 1);
        $semester = $request->input('semester', 1);
        $type = $request->input('type'); // พื้นฐาน / เพิ่มเติม

        // query
        $subjects = Subject::with('group')
            ->where('class', $class)
            ->where('semester', $semester)

            // 🔥 filter ประเภท
            ->when($type, function ($q) use ($type) {
                $q->where('subject_type', $type);
            })

            // 🔥 เรียง: พื้นฐานก่อน → เพิ่มเติม
            ->orderByRaw("
                CASE 
                    WHEN subject_type = 'พื้นฐาน' THEN 1
                    ELSE 2
                END
            ")

            // 🔥 เรียงกลุ่ม + รหัส
            ->orderBy('group_id')
            ->orderBy('subject_code')

            ->get();

        return view('subjects.index', [
            'subjects' => $subjects,
            'class' => $class,
            'semester' => $semester,
            'type' => $type,
        ]);
    }
}