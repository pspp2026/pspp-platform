<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Schedule;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherScoreController extends Controller
{
    /**
     * แสดงรายวิชาที่ครูรับผิดชอบ
     */
    public function index()
    {
        $teacher = Auth::user();

        $schedules = Schedule::with([
                'subject',
                'classroom',
                'academicTerm',
                'period',
            ])
            ->where('teacher_id', $teacher->id)
            ->orderBy('day_of_week')
            ->orderBy('period_id')
            ->get();

        return view('teacher.scores.index', compact('schedules'));
    }

    /**
     * แสดงหน้าบันทึกคะแนน
     */
    public function show(Schedule $schedule)
    {
        // ตรวจสอบสิทธิ์
        abort_if(
            $schedule->teacher_id !== Auth::id(),
            403,
            'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้'
        );

        // โหลดความสัมพันธ์
        $schedule->load([
            'subject',
            'classroom',
            'academicTerm',
            'period',
        ]);

        // ดึงนักเรียนที่ลงทะเบียนในห้องเรียนนี้
        $students = Enrollment::with('student')
            ->active()
            ->where('classroom_id', $schedule->classroom_id)
            ->where('academic_term_id', $schedule->academic_term_id)
            ->get()
            ->pluck('student')
            ->filter()
            ->sortBy('student_code')
            ->values();

        // ดึงคะแนนเดิม
        $scores = Score::where('schedule_id', $schedule->id)
            ->get()
            ->keyBy('student_id');

        return view(
            'teacher.scores.show',
            compact(
                'schedule',
                'students',
                'scores'
            )
        );
    }
        /**
     * ไม่ใช้งาน
     */
    public function create()
    {
        return redirect()->route('teacher.scores.index');
    }

    /**
     * ไม่ใช้งาน
     */
    public function store(Request $request)
    {
        return redirect()->route('teacher.scores.index');
    }

    /**
     * ไม่ใช้งาน
     */
    public function edit(Schedule $schedule)
    {
        return redirect()->route('teacher.scores.show', $schedule);
    }

    /**
     * บันทึกคะแนนนักเรียนทั้งห้อง
     */
    public function update(Request $request, Schedule $schedule)
    {
        // ตรวจสอบสิทธิ์
        abort_if(
            $schedule->teacher_id !== Auth::id(),
            403,
            'คุณไม่มีสิทธิ์แก้ไขข้อมูลนี้'
        );

        $request->validate([
            'students' => ['required', 'array'],
        ]);

        DB::transaction(function () use ($request, $schedule) {

            foreach ($request->students as $studentId) {

                $work = (float) ($request->work_score[$studentId] ?? 0);
                $mid = (float) ($request->midterm_score[$studentId] ?? 0);
                $final = (float) ($request->final_score[$studentId] ?? 0);

                $attendance = (float) ($request->attendance_score[$studentId] ?? 0);
                $behavior = (float) ($request->behavior_score[$studentId] ?? 0);

                $extra = (float) ($request->extra_score[$studentId] ?? 0);
                $deduction = (float) ($request->deduction_score[$studentId] ?? 0);

                $total =
                    $work +
                    $mid +
                    $final +
                    $attendance +
                    $behavior +
                    $extra -
                    $deduction;

                Score::updateOrCreate(
                    [
                        'schedule_id' => $schedule->id,
                        'student_id'  => $studentId,
                    ],
                    [
                        'work_score'       => $work,
                        'midterm_score'    => $mid,
                        'final_score'      => $final,
                        'attendance_score' => $attendance,
                        'behavior_score'   => $behavior,
                        'extra_score'      => $extra,
                        'deduction_score'  => $deduction,
                        'total_score'      => $total,
                    ]
                );
            }
        });

        return redirect()
            ->route('teacher.scores.show', $schedule)
            ->with('success', 'บันทึกคะแนนเรียบร้อยแล้ว');
    }

    /**
     * ไม่อนุญาตให้ลบคะแนนผ่านหน้านี้
     */
    public function destroy(Score $score)
    {
        return redirect()
            ->route('teacher.scores.index')
            ->with('error', 'ไม่สามารถลบคะแนนได้');
    }
}