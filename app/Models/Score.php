<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

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
    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    /**
     * นักเรียน
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * ผลการเรียน
     */
    public function grade()
    {
        return $this->hasOne(Grade::class);
    }


}