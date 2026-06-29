<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Classroom;
use App\Models\Schedule;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class StudentEnrollmentService
{
    /**
     * รายชื่อนักเรียนของโรงเรียน
     */
    public function studentsBySchool(
        int $schoolId,
        int $perPage = 20
    ): LengthAwarePaginator {

        return Student::with('classroom')
            ->where('school_id', $schoolId)
            ->orderBy('student_code')
            ->paginate($perPage);
    }

    /**
     * ห้องเรียนของโรงเรียน
     */
    public function classroomsBySchool(
        int $schoolId
    ): Collection {

        return Classroom::where('school_id', $schoolId)
            ->orderBy('level')
            ->orderBy('name')
            ->get();
    }

    /**
     * จัดนักเรียนเข้าห้อง
     */
    public function assignClassroom(
        Student $student,
        ?int $classroomId
    ): Student {

        $student->update([
            'classroom_id' => $classroomId,
        ]);

        return $student->refresh();
    }

    /**
     * ยกเลิกการจัดห้อง
     */
    public function removeClassroom(
        Student $student
    ): Student {

        $student->update([
            'classroom_id' => null,
        ]);

        return $student->refresh();
    }

    /**
     * ตรวจว่านักเรียนอยู่โรงเรียนเดียวกับผู้ใช้งาน
     */
    public function belongsToSchool(
        Student $student,
        int $schoolId
    ): bool {

        return $student->school_id == $schoolId;
    }

    /**
     * จำนวนนักเรียนที่ยังไม่ได้จัดห้อง
     */
    public function unassignedCount(
        int $schoolId
    ): int {

        return Student::where('school_id', $schoolId)
            ->whereNull('classroom_id')
            ->count();
    }

    /**
     * รายชื่อนักเรียนในห้องเรียน
     */
    public function studentsByClassroom(
        int $schoolId,
        int $classroomId
    )
    {
        return Student::where('school_id', $schoolId)
            ->where('classroom_id', $classroomId)
            ->orderBy('student_code')
            ->get();
    }


    /**
     * รายชื่อนักเรียนจากตารางสอน
     */
    public function studentsBySchedule(
        Schedule $schedule
    )
    {
        return Student::where('school_id', $schedule->classroom->school_id)
            ->where('classroom_id', $schedule->classroom_id)
            ->orderBy('student_code')
            ->get();
    }

}