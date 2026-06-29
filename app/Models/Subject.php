<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $table = 'subjects';

    protected $fillable = [
        'subject_code',
        'subject_name',
        'group_id',
        'class',
        'semester',
        'subject_type',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function group()
    {
        return $this->belongsTo(SubjectGroup::class, 'group_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (คำนวณจากรหัสวิชา)
    |--------------------------------------------------------------------------
    */

    // 🔹 หลักที่ 2 → ระดับ (ช่วงชั้น)
    public function getLevelAttribute()
    {
        return substr($this->subject_code, 1, 1);
    }

    // 🔹 หลักที่ 3 → ปี (1–3)
    public function getCurriculumYearAttribute()
    {
        return (int) substr($this->subject_code, 2, 1);
    }

    // 🔹 คำนวณ class (ม.1–ม.6)
    public function getClassNameAttribute()
    {
        $level = (int) $this->level;

        $year = $this->curriculum_year;

        $class = ($level == 2 ? 0 : 3) + $year;

        return 'ม.' . $class;
    }

    // 🔹 ประเภทวิชา (backup ถ้า DB ไม่มีค่า)
    public function getSubjectTypeAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }

        $type = substr($this->subject_code, 3, 1);

        return $type == '1' ? 'พื้นฐาน' : 'เพิ่มเติม';
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    // 🔹 ใช้ filter
    public function isBasic()
    {
        return $this->subject_type === 'พื้นฐาน';
    }

    public function isAdditional()
    {
        return $this->subject_type === 'เพิ่มเติม';
    }
    //
    public function units()
    {
        return $this->hasMany(LearningUnit::class);
    }

    //* ความสัมพันธ์กับ teachers (ผ่าน pivot table)
    public function teachers()
    {        return $this->belongsToMany(Teacher::class, 'teacher_subjects');
    }   

//*schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }


}