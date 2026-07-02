<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Classroom;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
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
     * รายชื่อห้องเรียนของโรงเรียนที่ Admin กำลังดูแล
     */
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        $classrooms = Classroom::where('school_id', $schoolId)
            ->orderBy('level')
            ->orderBy('name')
            ->get();

        $subjects = Subject::orderBy('subject_name')->get();

        $teachers = Teacher::where('school_id', $schoolId)
            ->orderBy('teacher_code')
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
        $schoolId = auth()->user()->school_id;

        abort_if($classroom->school_id != $schoolId, 403);

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

        $currentTermId = $request->term
            ?? AcademicTerm::where('is_active', 1)->value('id');

        $currentTerm = AcademicTerm::find($currentTermId);

        if (!$currentTerm) {
            return redirect()
                ->route('admin.schedules.index')
                ->with('error', 'ไม่พบข้อมูลภาคเรียนปัจจุบัน');
        }

        $subjects = Subject::where('class', $classLevel)
            ->where('semester', $currentTerm->semester)
            ->orderBy('subject_name')
            ->get();

        $teachers = User::where('role', 'teacher')
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $academicTerms = AcademicTerm::orderByDesc('academic_year')
            ->orderBy('semester')
            ->get();

        $schedules = Schedule::with([
                'subject',
                'teacher',
                'period',
                'academicTerm',
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
        $schoolId = auth()->user()->school_id;

        $validated = $request->validate([
            'academic_term_id' => 'required|exists:academic_terms,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
            'day_of_week' => 'required',
        ]);

        $classroom = Classroom::where('school_id', $schoolId)
            ->findOrFail($validated['classroom_id']);

        $teacherExists = User::where('id', $validated['teacher_id'])
            ->where('role', 'teacher')
            ->where('school_id', $schoolId)
            ->exists();

        abort_unless($teacherExists, 403);

        if ($this->scheduleService->classroomConflict([
            'classroom_id' => $validated['classroom_id'],
            'day_of_week' => $validated['day_of_week'],
            'period_id' => $validated['period_id'],
            'academic_term_id' => $validated['academic_term_id'],
        ])) {
            return back()->with('error', 'คาบนี้มีตารางสอนอยู่แล้ว');
        }

        if ($this->scheduleService->teacherConflict([
            'teacher_id' => $validated['teacher_id'],
            'academic_term_id' => $validated['academic_term_id'],
            'day_of_week' => $validated['day_of_week'],
            'period_id' => $validated['period_id'],
        ])) {
            return back()->with('error', 'ครูท่านนี้มีตารางสอนในคาบนี้แล้ว');
        }

        $this->scheduleService->store([
            'academic_term_id' => $validated['academic_term_id'],
            'classroom_id' => $classroom->id,
            'subject_id' => $validated['subject_id'],
            'teacher_id' => $validated['teacher_id'],
            'period_id' => $validated['period_id'],
            'day_of_week' => $validated['day_of_week'],
        ]);

        return back()->with('success', 'บันทึกตารางสอนสำเร็จ');
    }

    /**
     * ฟอร์มคัดลอกตารางสอน
     */
    public function copyForm()
    {
        $terms = AcademicTerm::orderByDesc('academic_year')
            ->orderBy('semester')
            ->get();

        return view('admin.schedules.copy', compact('terms'));
    }

    /**
     * คัดลอกตารางสอนเฉพาะโรงเรียนของ Admin
     */
    public function copyStore(Request $request)
    {
        $request->validate([
            'from_term' => 'required|exists:academic_terms,id',
            'to_term' => 'required|exists:academic_terms,id',
        ]);

        if ($request->from_term == $request->to_term) {
            return back()->with(
                'error',
                'ไม่สามารถเลือกภาคเรียนต้นทางและปลายทางเป็นภาคเรียนเดียวกันได้'
            );
        }

        try {
            $schoolId = auth()->user()->school_id;

            $count = $this->scheduleService->copySchedule(
                $request->from_term,
                $request->to_term,
                $schoolId
            );

            return redirect()
                ->route('admin.schedules.index')
                ->with('success', "คัดลอกตารางสอนสำเร็จ จำนวน {$count} รายการ");

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * ฟอร์มแก้ไขตารางสอน
     */
    public function edit(Schedule $schedule)
    {
        $schoolId = auth()->user()->school_id;

        $schedule->load('classroom');

        abort_if(!$schedule->classroom || $schedule->classroom->school_id != $schoolId, 403);

        $subjects = Subject::orderBy('subject_name')->get();

        $teachers = User::where('role', 'teacher')
            ->where('school_id', $schoolId)
            ->orderBy('name')
            ->get();

        $academicTerms = AcademicTerm::orderByDesc('academic_year')
            ->orderBy('semester')
            ->get();

        $periods = Period::orderBy('id')->get();

        $classrooms = Classroom::where('school_id', $schoolId)
            ->orderBy('level')
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
        $schoolId = auth()->user()->school_id;

        $schedule->load('classroom');

        abort_if(!$schedule->classroom || $schedule->classroom->school_id != $schoolId, 403);

        $validated = $request->validate([
            'academic_term_id' => 'required|exists:academic_terms,id',
            'classroom_id' => 'required|exists:classrooms,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:users,id',
            'period_id' => 'required|exists:periods,id',
            'day_of_week' => 'required',
        ]);

        Classroom::where('school_id', $schoolId)
            ->findOrFail($validated['classroom_id']);

        $teacherExists = User::where('id', $validated['teacher_id'])
            ->where('role', 'teacher')
            ->where('school_id', $schoolId)
            ->exists();

        abort_unless($teacherExists, 403);

        $validated['ignore_id'] = $schedule->id;

        $result = $this->scheduleService->validateConflict($validated);

        if (!$result['status']) {
            return back()
                ->withInput()
                ->withErrors([
                    $result['field'] => $result['message'],
                ]);
        }

        unset($validated['ignore_id']);

        $this->scheduleService->update($schedule, $validated);

        return redirect()
            ->route('admin.schedules.timetable', $schedule->classroom_id)
            ->with('success', 'แก้ไขตารางสอนเรียบร้อย');
    }

    /**
     * ลบตารางสอน
     */
    public function destroy(Schedule $schedule)
    {
        $schoolId = auth()->user()->school_id;

        $schedule->load('classroom');

        abort_if(!$schedule->classroom || $schedule->classroom->school_id != $schoolId, 403);

        $this->scheduleService->destroy($schedule);

        return back()->with('success', 'ลบตารางสอนสำเร็จ');
    }
}