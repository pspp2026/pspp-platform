<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// 🔥 Models
use App\Models\Province;
use App\Models\School;
use App\Models\User;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Schedule;
use App\Models\AcademicTerm;

class DashboardController extends Controller
{
   public function index()
    {
        $user = Auth::user();

        $teacher = $user->teacher;

        if (!$teacher) {
            return back()->withErrors([
                'error' => 'ไม่พบข้อมูลครู'
            ]);
        }

        // จำนวนวิชาที่สอน
        $subjectCount = $teacher->subjects()->count();

        // ภาคเรียนปัจจุบัน
        $currentTerm = AcademicTerm::where('is_active', 1)->first();

        // ตารางสอนทั้งหมดของภาคเรียน
        $schedules = collect();

        // ตารางสอนวันนี้
        $todaySchedules = collect();

        if ($currentTerm) {

            // ตารางสอนทั้งหมด
            $schedules = Schedule::with([
                    'subject',
                    'classroom',
                    'period',
                    'academicTerm'
                ])
                ->where('teacher_id', $teacher->id)
                ->where('academic_term_id', $currentTerm->id)
                ->orderBy('day_of_week')
                ->orderBy('period_id')
                ->get();

            // วันปัจจุบัน (Monday, Tuesday, ...)
            $today = now()->englishDayOfWeek;

            // ตารางสอนวันนี้
            $todaySchedules = $schedules
                ->where('day_of_week', $today)
                ->values();
        }

        return view(
            'teacher.dashboard',
            compact(
                'subjectCount',
                'schedules',
                'todaySchedules'
            )
        );
    }
        
       /** จัดการวิชาที่สอน */
    public function manageSubjects()
    {
        $user = Auth::user();

        $teacher = $user->teacher;

        if (!$teacher) {
            return back()->withErrors([
                'error'=>'ไม่พบข้อมูลครู'
            ]);
        }


        // เรียงตามกลุ่มสาระ
        $subjects = Subject::with('group')
            ->orderBy('group_id')
            ->orderBy('subject_code')
            ->get();


        // วิชาที่เลือกไว้แล้ว
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

    /**
     * วิชาที่ฉันสอน
     */
    public function mySubjects()
    {
        $teacher = Auth::user()->teacher;

        $currentTermId = AcademicTerm::where('is_active', 1)
            ->value('id');

        $subjectIds = Schedule::where('teacher_id', $teacher->id)
            ->where('academic_term_id', $currentTermId)
            ->distinct()
            ->pluck('subject_id');

        $subjects = Subject::with([
                'units',
                'group'
            ])
            ->whereIn('id', $subjectIds)
            ->orderBy('subject_name')
            ->get();

           $teacher = Auth::user()->teacher;

            $subjectCount = Schedule::where('teacher_id', $teacher->id)
                ->distinct('subject_id')
                ->count('subject_id');

                $currentTerm = AcademicTerm::where('is_active', 1)->first();

            $todaySchedules = collect();

            if ($currentTerm) {

                $today = now()->englishDayOfWeek;

                $todaySchedules = Schedule::with([
                        'subject',
                        'classroom',
                        'period'
                    ])
                    ->where('teacher_id', $teacher->id)
                    ->where('academic_term_id', $currentTerm->id)
                    ->where('day_of_week', $today)
                    ->orderBy('period_id')
                    ->get();

            }
            $schedules = Schedule::with([
                    'subject',
                    'classroom',
                    'period',
                    'academicTerm'
                ])
                ->where('teacher_id', $teacher->id)
                ->orderBy('academic_term_id')
                ->orderBy('day_of_week')
                ->get()
                ->sortBy(['academicTerm.semester', 'asc'])
                ->groupBy(function ($schedule) {
                    return $schedule->academicTerm->academic_year . '-' .
                        $schedule->academicTerm->semester;
                });

        return view(
            'teacher.subjects.index',
            compact('subjects')
        );
    }
}