<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Schedule;
use App\Models\Score;
use App\Services\GradeService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TeacherGradeController extends Controller
{
    /**
     * Grade Service
     */
    protected GradeService $gradeService;

    /**
     * Constructor
     */
    public function __construct(GradeService $gradeService)
    {
        $this->gradeService = $gradeService;
    }

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

        return view('teacher.grades.index', compact('schedules'));
    }

    /**
     * แสดงผลการเรียนของห้อง
     */
    public function show(Schedule $schedule)
    {
        abort_if(
            $schedule->teacher_id !== Auth::id(),
            403,
            'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้'
        );

        $schedule->load([
            'subject',
            'classroom',
            'academicTerm',
            'period',
        ]);

        $scores = Score::with([
                'student',
                'grade',
            ])
            ->where('schedule_id', $schedule->id)
            ->orderBy('student_id')
            ->get();

        return view(
            'teacher.grades.show',
            compact(
                'schedule',
                'scores'
            )
        );
    }

    /**
     * คำนวณเกรดทั้งห้อง
     */
    public function calculate(Schedule $schedule)
    {
        abort_if(
            $schedule->teacher_id !== Auth::id(),
            403,
            'คุณไม่มีสิทธิ์ดำเนินการ'
        );

        DB::transaction(function () use ($schedule) {

            $scores = Score::where('schedule_id', $schedule->id)->get();

            foreach ($scores as $score) {

                $result = $this->gradeService->calculate(
                    $score->total_score
                );

                Grade::updateOrCreate(

                    [
                        'score_id' => $score->id,
                    ],

                    [
                        'grade'         => $result['grade'],
                        'grade_point'   => $result['grade_point'],
                        'passed'        => $result['passed'],
                        'calculated_at' => now(),
                    ]

                );
            }
        });

        return redirect()
            ->route('teacher.grades.show', $schedule)
            ->with(
                'success',
                'คำนวณผลการเรียนเรียบร้อยแล้ว'
            );
    }
}