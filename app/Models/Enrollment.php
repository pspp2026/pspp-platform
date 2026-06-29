<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $fillable = [
        'student_id',
        'school_id',
        'classroom_id',
        'academic_term_id',
        'grade_level',
        'semester',
        'academic_year',
        'status',
    ];

    protected $casts = [
        'academic_year' => 'integer',
        'semester' => 'integer',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getClassroomNameAttribute()
    {
        return $this->classroom?->name;
    }
}