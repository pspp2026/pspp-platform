<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Student;

class Classroom extends Model
{
    protected $fillable = [
        'school_id',
        'classroom_id',
        'teacher_id',
        'name',
        'level',
        'room',
        'academic_year',
        'student_count',
        'status'
    ];

    protected $appends = ['full_name'];

    public function getFullNameAttribute()
    {
        return $this->level.'/'.$this->room;
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}