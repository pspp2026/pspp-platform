<?php

namespace App\Services;

use App\Models\AttendanceRecord;
use App\Models\AttendanceSession;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    /**
     * สร้างหรือดึง Session ของการเช็กชื่อ
     */
    public function createSession(array $data): AttendanceSession
    {
        return AttendanceSession::firstOrCreate(
            [
                'schedule_id'     => $data['schedule_id'],
                'attendance_date' => $data['attendance_date'],
                'period_id'       => $data['period_id'],
            ],
            [
                'school_id'        => $data['school_id'],
                'academic_term_id' => $data['academic_term_id'],
                'classroom_id'     => $data['classroom_id'],
                'subject_id'       => $data['subject_id'],
                'teacher_id'       => $data['teacher_id'],
                'topic'            => $data['topic'] ?? null,
                'note'             => $data['note'] ?? null,
                'status'           => 'draft',
            ]
        );
    }

    /**
     * โหลดนักเรียนในห้อง
     */
    public function getStudents(int $classroomId, int $academicTermId)
    {
        return Enrollment::with([
                'student.user',
                'student.temple',
                'classroom',
            ])
            ->where('classroom_id', $classroomId)
            ->where('academic_term_id', $academicTermId)
            ->active()
            ->orderBy('student_id')
            ->get();
    }

    /**
     * บันทึกผลการเช็กชื่อ
     */
    public function storeAttendance(
        AttendanceSession $session,
        array $students
    ): void {

        DB::transaction(function () use ($session, $students) {

            foreach ($students as $studentId => $data) {

                AttendanceRecord::updateOrCreate(

                    [
                        'attendance_session_id' => $session->id,
                        'student_id'            => $studentId,
                    ],

                    [
                        'status'      => $data['status'],
                        'remark'      => $data['remark'] ?? null,
                        'recorded_at' => $data['recorded_at'] ?? now(),
                    ]
                );
            }

        });

    }

    /**
     * ปิด Session
     */
    public function completeSession(
        AttendanceSession $session
    ): AttendanceSession {

        $session->update([
            'status' => 'completed',
        ]);

        return $session->fresh();
    }

    /**
     * เปิด Session ใหม่
     */
    public function reopenSession(
        AttendanceSession $session
    ): AttendanceSession {

        $session->update([
            'status' => 'draft',
        ]);

        return $session->fresh();
    }

    /**
     * สรุปผลการเช็กชื่อ
     */
    public function summary(
        AttendanceSession $session
    ): array {

        $records = AttendanceRecord::where(
            'attendance_session_id',
            $session->id
        );

        return [

            'present' => (clone $records)
                ->where('status', AttendanceRecord::STATUS_PRESENT)
                ->count(),

            'late' => (clone $records)
                ->where('status', AttendanceRecord::STATUS_LATE)
                ->count(),

            'leave' => (clone $records)
                ->where('status', AttendanceRecord::STATUS_LEAVE)
                ->count(),

            'absent' => (clone $records)
                ->where('status', AttendanceRecord::STATUS_ABSENT)
                ->count(),

            'total' => (clone $records)->count(),

        ];
    }

    /**
     * ประวัติการเช็กชื่อของครู
     */
    public function teacherHistory(int $teacherId)
    {
        return AttendanceSession::with([
                'classroom',
                'subject',
                'period',
            ])
            ->where('teacher_id', $teacherId)
            ->latest('attendance_date')
            ->paginate(20);
    }

    /**
     * ประวัติการเข้าเรียนของนักเรียน
     */
    public function studentHistory(int $studentId)
    {
        return AttendanceRecord::with([
                'session.subject',
                'session.classroom',
                'session.period',
            ])
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(30);
    }
}