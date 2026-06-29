<?php

namespace App\Services\Survey;

use App\Models\Survey\Survey;
use Illuminate\Support\Facades\Auth;

class SurveyService
{
    /**
     * ดึงรายการแบบสอบถามทั้งหมด
     */
    public function getAll()
    {
        return Survey::with(['creator', 'school'])
            ->latest()
            ->paginate(15);
    }

    /**
     * สร้างแบบสอบถาม
     */
    public function store(array $data): Survey
    {
        return Survey::create([
            'school_id'   => Auth::user()->school_id,
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'objective'   => $data['objective'] ?? null,
            'target_type' => $data['target_type'] ?? 'all',
            'status'      => 'draft',
            'is_public'   => !empty($data['is_public']),
            'start_at'    => $data['start_at'] ?? null,
            'end_at'      => $data['end_at'] ?? null,
            'created_by'  => Auth::id(),
        ]);
    }

    /**
     * แก้ไขแบบสอบถาม
     */
    public function update(Survey $survey, array $data): bool
    {
        return $survey->update([
            'title'       => $data['title'],
            'description' => $data['description'] ?? null,
            'objective'   => $data['objective'] ?? null,
            'target_type' => $data['target_type'],
            'status'      => $data['status'] ?? $survey->status,
            'is_public'   => !empty($data['is_public']),
            'start_at'    => $data['start_at'] ?? null,
            'end_at'      => $data['end_at'] ?? null,
        ]);
    }

    /**
     * ลบแบบสอบถาม
     */
    public function delete(Survey $survey): ?bool
    {
        return $survey->delete();
    }

    /**
     * เปลี่ยนสถานะแบบสอบถาม
     */
    public function changeStatus(Survey $survey, string $status): bool
    {
        return $survey->update([
            'status' => $status,
        ]);
    }

    /**
     * เปิดแบบสอบถาม
     */
    public function publish(Survey $survey): bool
    {
        return $this->changeStatus($survey, 'published');
    }

    /**
     * ปิดแบบสอบถาม
     */
    public function close(Survey $survey): bool
    {
        return $this->changeStatus($survey, 'closed');
    }

    /**
     * กลับเป็น Draft
     */
    public function draft(Survey $survey): bool
    {
        return $this->changeStatus($survey, 'draft');
    }
}