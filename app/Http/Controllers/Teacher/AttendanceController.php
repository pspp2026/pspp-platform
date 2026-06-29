<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\AttendanceSession;
use App\Models\Schedule;
use App\Models\Teacher;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    protected AttendanceService $attendanceService;

    public function __construct(
        AttendanceService $attendanceService
    ) {
        $this->attendanceService = $attendanceService;
    }

    /**
     * ----------------------------------------------------------
     * รายการเช็กชื่อของครู
     * ----------------------------------------------------------
     */
    public function index()
    {
        $sessions = $this->attendanceService
            ->teacherHistory(Auth::id());

        return view(
            'teacher.attendance.index',
            compact('sessions')
        );
    }

    /**
     * ----------------------------------------------------------
     * หน้าเช็กชื่อ
     * ----------------------------------------------------------
     */
    public function takeAttendance(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'date'        => 'required|date',
        ]);

        $schedule = Schedule::with([
            'classroom',
            'subject',
            'teacher',
            'period',
            'academicTerm',
        ])->findOrFail(
            $request->schedule_id
        );

        // ป้องกันไม่ให้ครูเช็กชื่อแทนกัน
        if ($schedule->teacher_id != Auth::id()) {

            abort(403);

        }
        /**
         * แปลง users.id -> teachers.id
         */
        $teacher = Teacher::where(
            'user_id',
            $schedule->teacher_id
        )->firstOrFail();


        /**
         * สร้าง Session
         */
        
        $session = $this->attendanceService
            ->createSession([

                'school_id' => $schedule->classroom->school_id,

                'academic_term_id' => $schedule->academic_term_id,

                'schedule_id'      => $schedule->id,

                'classroom_id'     => $schedule->classroom_id,

                'subject_id'       => $schedule->subject_id,

                'teacher_id' => $teacher->id,

                'period_id'        => $schedule->period_id,

                'attendance_date'  => $request->date,

                'topic'            => $request->topic,

                'note'             => $request->note,

            ]);

        /**
         * โหลดรายชื่อนักเรียน
         */
        $students = $this->attendanceService
            ->getStudents(
                $schedule->classroom_id,
                $schedule->academic_term_id
            );

        return view(
            'teacher.attendance.create',
            compact(
                'session',
                'schedule',
                'students'
            )
        );
    }

    /**
     * ----------------------------------------------------------
     * บันทึกการเช็กชื่อ
     * ----------------------------------------------------------
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([

            'attendance_session_id'
                => 'required|exists:attendance_sessions,id',

            'students'
                => 'required|array',

        ]);

        $session = AttendanceSession::findOrFail(
            $request->attendance_session_id
        );

        /**
         * ตรวจสอบสิทธิ์
         */
        if ($session->teacher_id != Auth::id()) {

            abort(403);

        }

        $this->attendanceService
            ->storeAttendance(

                $session,

                $request->students

            );

        $this->attendanceService
            ->completeSession($session);

        return redirect()

            ->route(
                'teacher.attendances.show',
                $session->id
            )

            ->with(
                'success',
                'บันทึกการเช็กชื่อเรียบร้อยแล้ว'
            );
    }
        /**
     * ----------------------------------------------------------
     * รายละเอียดการเช็กชื่อ
     * ----------------------------------------------------------
     */
    public function show(int $sessionId)
    {
        $session = AttendanceSession::with([
            'school',
            'academicTerm',
            'classroom',
            'subject',
            'teacher',
            'period',
            'records.student.user',
        ])->findOrFail($sessionId);

        if ($session->teacher_id != Auth::id()) {
            abort(403);
        }

        $summary = $this->attendanceService
            ->summary($session);

        return view(
            'teacher.attendance.show',
            compact(
                'session',
                'summary'
            )
        );
    }

    /**
     * ----------------------------------------------------------
     * ประวัติการเช็กชื่อ
     * ----------------------------------------------------------
     */
    public function history()
    {
        $sessions = $this->attendanceService
            ->teacherHistory(Auth::id());

        return view(
            'teacher.attendance.history',
            compact('sessions')
        );
    }

    /**
     * ----------------------------------------------------------
     * รายงานการเข้าเรียน
     * ----------------------------------------------------------
     */
    public function report()
    {
        $sessions = $this->attendanceService
            ->teacherHistory(Auth::id());

        return view(
            'teacher.attendance.report',
            compact('sessions')
        );
    }

/**
 * ----------------------------------------------------------
 * แก้ไขการเช็กชื่อ
 * ----------------------------------------------------------
 */
public function edit(int $sessionId)
{
    $session = AttendanceSession::with([
        'school',
        'academicTerm',
        'subject',
        'classroom',
        'teacher',
        'period',
        'records.student.user',
    ])
    ->findOrFail($sessionId);

    // ตรวจสอบสิทธิ์
    if ($session->teacher_id != Auth::id()) {
        abort(403);
    }

    return view(
        'teacher.attendance.edit',
        compact('session')
    );
}

/**
 * ----------------------------------------------------------
 * บันทึกการแก้ไขการเช็กชื่อ
 * ----------------------------------------------------------
 */
public function update(
    Request $request,
    int $sessionId
)
{
    $request->validate([
        'students' => 'required|array',
    ]);

    $session = AttendanceSession::findOrFail($sessionId);

    // ตรวจสอบสิทธิ์
    if ($session->teacher_id != Auth::id()) {
        abort(403);
    }

    // อัปเดตผลการเช็กชื่อ
$this->attendanceService->storeAttendance(
    $session,
    $request->students
);

// ปิด Session หลังแก้ไข
$this->attendanceService->completeSession(
    $session
);

return redirect()
    ->route(
        'teacher.attendances.show',
        $session->id
    )
    ->with(
        'success',
        'แก้ไขข้อมูลการเช็กชื่อเรียบร้อยแล้ว'
    );
}

}
