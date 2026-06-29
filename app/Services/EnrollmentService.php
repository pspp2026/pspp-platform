<?php

namespace App\Services;

use App\Models\AcademicTerm;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    /**
     * ภาคเรียนทั้งหมด
     */
    public function getTerms()
    {
        return AcademicTerm::orderByDesc('academic_year')
            ->orderBy('semester')
            ->get();
    }

    /**
     * ห้องเรียนทั้งหมด
     */
    public function getClassrooms()
    {
        return Classroom::orderBy('level')
            ->orderBy('room')
            ->get();
    }

    /**
     * นักเรียนที่ยังไม่ได้จัดเข้าห้องในภาคเรียนนี้
     */
    public function getUnassignedStudents(int $academicTermId)
    {
        $studentIds = Enrollment::where('academic_term_id', $academicTermId)
            ->pluck('student_id');

        return Student::with(['user'])
            ->whereNotIn('id', $studentIds)
            ->orderBy('student_code')
            ->get();
    }

    /**
     * นักเรียนในห้อง
     */
    public function getStudentsInClass(
        int $classroomId,
        int $academicTermId
    ) {
        return Enrollment::with([
                'student.user',
                'student.temple',
                'classroom',
                'academicTerm',
            ])
            ->where('classroom_id', $classroomId)
            ->where('academic_term_id', $academicTermId)
            ->where('status', 'active')
            ->orderBy('student_id')
            ->get();
    }

    /**
     * จัดนักเรียนเข้าห้อง
     */
    public function assignStudents(array $data): void
    {
        DB::transaction(function () use ($data) {

            $term = AcademicTerm::findOrFail($data['academic_term_id']);

            $classroom = Classroom::findOrFail($data['classroom_id']);

            foreach ($data['student_ids'] as $studentId) {

                $student = Student::findOrFail($studentId);

                $exists = Enrollment::where('student_id', $student->id)
                    ->where('academic_term_id', $term->id)
                    ->exists();

                if ($exists) {
                    continue;
                }

                Enrollment::create([
                    'student_id'       => $student->id,
                    'school_id'        => $student->school_id,
                    'classroom_id'     => $classroom->id,
                    'academic_term_id' => $term->id,

                    // Business Logic
                    'grade_level'      => $classroom->level,
                    'semester'         => $term->semester,
                    'academic_year'    => $term->academic_year,

                    'status'           => 'active',
                ]);
            }

            $this->updateStudentCount($classroom->id);
        });
    }

    /**
     * นำนักเรียนออกจากห้อง
     */
    public function removeStudent(int $enrollmentId): void
    {
        DB::transaction(function () use ($enrollmentId) {

            $enrollment = Enrollment::findOrFail($enrollmentId);

            $classroomId = $enrollment->classroom_id;

            $enrollment->delete();

            $this->updateStudentCount($classroomId);
        });
    }

    /**
     * อัปเดตจำนวนนักเรียนของห้อง
     */
    public function updateStudentCount(int $classroomId): void
    {
        $count = Enrollment::where('classroom_id', $classroomId)
            ->where('status', 'active')
            ->count();

        Classroom::whereKey($classroomId)
            ->update([
                'student_count' => $count,
            ]);
    }

    /**
     * ตรวจสอบว่านักเรียนลงทะเบียนแล้วหรือยัง
     */
    public function isEnrolled(
        int $studentId,
        int $academicTermId
    ): bool {
        return Enrollment::where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->exists();
    }

    /**
     * Enrollment ของนักเรียน
     */
    public function getEnrollment(
        int $studentId,
        int $academicTermId
    ): ?Enrollment {
        return Enrollment::with([
                'classroom',
                'academicTerm',
            ])
            ->where('student_id', $studentId)
            ->where('academic_term_id', $academicTermId)
            ->first();
    }

    /**
     * จำนวนนักเรียนในห้องของภาคเรียน
     */
    public function studentCount(
        int $classroomId,
        int $academicTermId
    ): int {
        return Enrollment::where('classroom_id', $classroomId)
            ->where('academic_term_id', $academicTermId)
            ->where('status', 'active')
            ->count();
    }

    /**
     * ย้ายนักเรียนไปห้องใหม่
     */
    public function moveStudent(
        int $enrollmentId,
        int $newClassroomId
    ): void {
        DB::transaction(function () use ($enrollmentId, $newClassroomId) {

            $enrollment = Enrollment::findOrFail($enrollmentId);

            $oldClassroomId = $enrollment->classroom_id;

            $classroom = Classroom::findOrFail($newClassroomId);

            $enrollment->update([
                'classroom_id' => $classroom->id,
                'grade_level'  => $classroom->level,
            ]);

            $this->updateStudentCount($oldClassroomId);
            $this->updateStudentCount($classroom->id);
        });
    }

    /**
     * รายชื่อนักเรียนทั้งหมดของภาคเรียน
     * ใช้ร่วมกับ Attendance / Score / Grade
     */
    public function getEnrollmentsByTerm(int $academicTermId)
    {
        return Enrollment::with([
                'student.user',
                'classroom',
                'academicTerm',
            ])
            ->where('academic_term_id', $academicTermId)
            ->where('status', 'active')
            ->get();
    }
}