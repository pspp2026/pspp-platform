<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AcademicTerm;
use App\Models\Classroom;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;
use App\Models\Teacher;

use App\Services\ScheduleService;

use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    protected ScheduleService $scheduleService;

    
    public function __construct(ScheduleService $scheduleService)
    {
        $this->scheduleService = $scheduleService;
    }

    /**
     * รายชื่อห้องเรียน
     */
    public function index()
    {
        $classrooms = Classroom::orderBy('level')
            ->orderBy('name')
            ->get();
        
        $subjects = Subject::orderBy('subject_name')
        ->get();
        $teachers = Teacher::orderBy('teacher_code')
        ->get();

        return view(
            'admin.schedules.index',
            compact('classrooms', 'subjects', 'teachers')
        );
        
    }

    /**
     * ตารางสอนรายห้อง
     */
    public function timetable(Request $request, Classroom $classroom)
    {
    $periods = Period::orderBy('id')->get();

    $levelMap = [
        'ม.1' => 1,
        'ม.2' => 2,
        'ม.3' => 3,
        'ม.4' => 4,
        'ม.5' => 5,
        'ม.6' => 6,
    ];

    $classLevel = $levelMap[$classroom->level] ?? null;

    // ภาคเรียนปัจจุบัน
    $currentTermId = $request->term
        ?? AcademicTerm::where('is_active', 1)->value('id');

    $currentTerm = AcademicTerm::find($currentTermId);

    // วิชาเฉพาะระดับชั้น + ภาคเรียน

    $subjects = Subject::where('class', $classLevel)
        ->where('semester', $currentTerm->semester)
        ->orderBy('subject_name')
        ->get();

    $teachers = User::where('role', 'teacher')
        ->orderBy('name')
        ->get();

    $academicTerms = AcademicTerm::orderByDesc('academic_year')
        ->orderBy('semester')
        ->get();

    // ตารางสอนเฉพาะภาคเรียน
    $schedules = Schedule::with([
            'subject',
            'teacher',
            'period',
            'academicTerm'
        ])
        ->where('classroom_id', $classroom->id)
        ->where('academic_term_id', $currentTermId)
        ->get();

    return view(
        'admin.schedules.timetable',
        compact(
            'classroom',
            'periods',
            'subjects',
            'teachers',
            'academicTerms',
            'currentTerm',
            'currentTermId',
            'schedules'
        )
    );

    }

    /**
     * บันทึกตารางสอน
     */
    public function store(Request $request)
    {
        $request->validate([
            'academic_term_id' => 'required|exists:academic_terms,id',
            'classroom_id'     => 'required|exists:classrooms,id',
            'subject_id'       => 'required|exists:subjects,id',
            'teacher_id'       => 'required|exists:users,id',
            'period_id'        => 'required|exists:periods,id',
            'day_of_week'      => 'required',
        ]);

        // ป้องกันคาบซ้ำ
        if(
            $this->scheduleService->classroomConflict([
                'classroom_id' => $request->classroom_id,
                'day_of_week' => $request->day_of_week,
                'period_id' => $request->period_id,
                'academic_term_id' => $request->academic_term_id
            ])
        ) {
        
            return back()->with(
                'error',
                'คาบนี้มีตารางสอนอยู่แล้ว'
            );
        }

        // ป้องกันครูซ้ำ

        if (
            $this->scheduleService->teacherConflict([
                'teacher_id'       => $request->teacher_id,
                'academic_term_id' => $request->academic_term_id,
                'day_of_week'      => $request->day_of_week,
                'period_id'        => $request->period_id,
            ])
        ) {
            return back()->with(
                'error',
                'ครูท่านนี้มีตารางสอนในคาบนี้แล้ว'
            );
        }

        
        $this->scheduleService->store([
            'academic_term_id' => $request->academic_term_id,
            'classroom_id'     => $request->classroom_id,
            'subject_id'       => $request->subject_id,
            'teacher_id'       => $request->teacher_id,
            'period_id'        => $request->period_id,
            'day_of_week'      => $request->day_of_week,
        ]);

        return back()->with(
            'success',
            'บันทึกตารางสอนสำเร็จ'
        );
    }

/**
 * คัดลอกตารางสอน
 */

public function copyForm()
{
    $terms = AcademicTerm::orderByDesc('academic_year')
        ->orderBy('semester')
        ->get();

    return view(
        'admin.schedules.copy',
        compact('terms')
    );
}

/**
 * คัดลอกตารางสอน
 */
public function copyStore(Request $request)
{
    $request->validate([
        'from_term' => 'required|exists:academic_terms,id',
        'to_term'   => 'required|exists:academic_terms,id',
    ]);

    if ($request->from_term == $request->to_term) {
        return back()->with(
            'error',
            'ไม่สามารถเลือกภาคเรียนต้นทางและปลายทางเป็นภาคเรียนเดียวกันได้'
        );
    }

    try {

        $count = $this->scheduleService->copySchedule(
            $request->from_term,
            $request->to_term
        );

        return redirect()
            ->route('admin.schedules.index')
            ->with(
                'success',
                "คัดลอกตารางสอนสำเร็จ จำนวน {$count} รายการ"
            );

    } catch (\Exception $e) {

        return back()
            ->withInput()
            ->with(
                'error',
                $e->getMessage()
            );

    }
}

/**
 * ฟอร์มแก้ไขตารางสอน
 */
public function edit(Schedule $schedule)
{
    $subjects = Subject::orderBy('subject_name')->get();

    $teachers = User::where('role', 'teacher')
        ->orderBy('name')
        ->get();

    $academicTerms = AcademicTerm::orderByDesc('academic_year')
        ->orderBy('semester')
        ->get();

    $periods = Period::orderBy('id')->get();

    $classrooms = Classroom::orderBy('level')
        ->orderBy('name')
        ->get();
        
    return view(
        'admin.schedules.edit',
        compact(
            'schedule',
            'subjects',
            'teachers',
            'academicTerms',
            'periods',
            'classrooms'
        )
    );
}

/**
 * แก้ไขตารางสอน
 */
public function update(Request $request, Schedule $schedule)
{
    $validated = $request->validate([
        'academic_term_id' => 'required|exists:academic_terms,id',
        'classroom_id'     => 'required|exists:classrooms,id',
        'subject_id'       => 'required|exists:subjects,id',
        'teacher_id'       => 'required|exists:users,id',
        'period_id'        => 'required|exists:periods,id',
        'day_of_week'      => 'required',
    ]);

    // ไม่ตรวจสอบกับ record ของตัวเอง
    $validated['ignore_id'] = $schedule->id;

    $result = $this->scheduleService->validateConflict($validated);

    if (!$result['status']) {
        return back()
            ->withInput()
            ->withErrors([
                $result['field'] => $result['message']
            ]);
    }

    // ignore_id ไม่ใช่ field ในฐานข้อมูล
    unset($validated['ignore_id']);

    $this->scheduleService->update($schedule, $validated);

    return redirect()
        ->route(
            'admin.schedules.timetable',
            $schedule->classroom_id
        )
        ->with(
            'success',
            'แก้ไขตารางสอนเรียบร้อย'
        );
}

    /**
     * ลบตารางสอน
     */
    public function destroy(Schedule $schedule)
    {
        $this->scheduleService->destroy($schedule);

        return back()->with(
            'success',
            'ลบตารางสอนสำเร็จ'
        );
    }
}