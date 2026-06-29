<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Schedule;

class TeacherScheduleController extends Controller
{
    /**
     * ----------------------------------------------------------
     * ตารางสอนของครู
     * ----------------------------------------------------------
     */
    public function index()
    {
        // ภาคเรียนที่ใช้งาน
        $term = AcademicTerm::where('is_active', 1)->first();

        $schedules = collect();

        if ($term) {

            $schedules = Schedule::with([
                    'subject',
                    'classroom',
                    'period',
                ])
                ->where('teacher_id', auth()->id())
                ->where('academic_term_id', $term->id)
                ->orderBy('day_of_week')
                ->orderBy('period_id')
                ->get();

        }

        // วันในสัปดาห์
        $days = [
            'Monday'    => 'จันทร์',
            'Tuesday'   => 'อังคาร',
            'Wednesday' => 'พุธ',
            'Thursday'  => 'พฤหัสบดี',
            'Friday'    => 'ศุกร์',
            'Saturday'  => 'เสาร์',
            'Sunday'    => 'อาทิตย์',
        ];

        // วันที่ปัจจุบัน (ใช้ส่งไป Attendance)
        $today = now()->toDateString();

        return view(
            'teacher.timetable.index',
            compact(
                'term',
                'schedules',
                'days',
                'today'
            )
        );
    }
}