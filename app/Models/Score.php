<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Score extends Model
{
    use HasFactory;

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'schedule_id',
        'student_id',

        'work_score',
        'midterm_score',
        'final_score',

        'attendance_score',
        'behavior_score',

        'extra_score',
        'deduction_score',

        'total_score',

        'remark',
    ];

    /**
     * ตารางสอน
     */
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * นักเรียน
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * ผลการเรียน (Grade)
     */
    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }

    /**
     * คะแนนรวมหลังหัก/บวก
     */
    public function getCalculatedTotalAttribute(): float
    {
        return
            ($this->work_score ?? 0)
            + ($this->midterm_score ?? 0)
            + ($this->final_score ?? 0)
            + ($this->attendance_score ?? 0)
            + ($this->behavior_score ?? 0)
            + ($this->extra_score ?? 0)
            - ($this->deduction_score ?? 0);
    }

    /**
     * เปอร์เซ็นต์คะแนน
     */
    public function getPercentAttribute(): float
    {
        return round($this->total_score ?? $this->calculated_total, 2);
    }
}