<?php

namespace App\Services;

use App\Models\Schedule;

use Illuminate\Support\Facades\DB;

class ScheduleService
{
    /**
     * บันทึกตารางสอน
     */
    public function store(array $data): Schedule
    {
        return Schedule::create($data);
    }

    /**
     * แก้ไขตารางสอน
     */
    public function update(Schedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    /**
     * ลบตารางสอน
     */
    public function destroy(Schedule $schedule): void
    {
        $schedule->delete();
    }

    /**
     * ตรวจสอบว่าห้องเรียนมีตารางสอนซ้ำหรือไม่
     */
    public function classroomConflict(array $data): bool
    {
        $query = Schedule::where('classroom_id', $data['classroom_id'])
            ->where('academic_term_id', $data['academic_term_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('period_id', $data['period_id']);

        // กรณีแก้ไขข้อมูล
        if (!empty($data['ignore_id'])) {
            $query->where('id', '!=', $data['ignore_id']);
        }

        return $query->exists();
    }

    /**
     * ตรวจสอบว่าครูมีตารางสอนซ้ำหรือไม่
     */
    public function teacherConflict(array $data): bool
    {
        $query = Schedule::where('teacher_id', $data['teacher_id'])
            ->where('academic_term_id', $data['academic_term_id'])
            ->where('day_of_week', $data['day_of_week'])
            ->where('period_id', $data['period_id']);

        // กรณีแก้ไขข้อมูล
        if (!empty($data['ignore_id'])) {
            $query->where('id', '!=', $data['ignore_id']);
        }

        return $query->exists();
    }

/**
 * คัดลอกตารางสอนจากภาคเรียนหนึ่งไปยังอีกภาคเรียนหนึ่ง
 */
public function copySchedule(int $fromTerm, int $toTerm): int
{
    return DB::transaction(function () use ($fromTerm, $toTerm) {

        // ตรวจสอบว่าปลายทางมีข้อมูลแล้วหรือไม่
        if (
            Schedule::where('academic_term_id', $toTerm)->exists()
        ) {
            throw new \Exception(
                'ภาคเรียนปลายทางมีตารางสอนอยู่แล้ว'
            );
        }

        // ตารางสอนต้นทาง
        $schedules = Schedule::where(
            'academic_term_id',
            $fromTerm
        )->get();

        $count = 0;

        foreach ($schedules as $schedule) {

            Schedule::create([

                'academic_term_id' => $toTerm,

                'classroom_id' => $schedule->classroom_id,

                'subject_id' => $schedule->subject_id,

                'teacher_id' => $schedule->teacher_id,

                'period_id' => $schedule->period_id,

                'day_of_week' => $schedule->day_of_week,

            ]);

            $count++;

        }

        return $count;

    });
}

    /**
     * ตรวจสอบความถูกต้องก่อนบันทึก
     */
    public function validateConflict(array $data): array
    {
        if ($this->classroomConflict($data)) {
            return [
                'status' => false,
                'field' => 'classroom_id',
                'message' => 'ห้องเรียนมีตารางสอนในคาบนี้แล้ว'
            ];
        }

        if ($this->teacherConflict($data)) {
            return [
                'status' => false,
                'field' => 'teacher_id',
                'message' => 'ครูผู้สอนไม่ว่างในคาบนี้'
            ];
        }

        return [
            'status' => true
        ];
    }
}