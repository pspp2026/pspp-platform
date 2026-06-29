<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    /**
     * --------------------------------------------------------------------------
     * Mass Assignment
     * --------------------------------------------------------------------------
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'classroom_id',
        'student_code',
        'prefix',
        'first_name',
        'last_name',
        'id_card',
        'birth_date',
        'nationality',
        'ethnicity',
        'temple_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * ผู้ใช้งาน
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * โรงเรียน
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * วัด
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    /**
     * ประวัติการลงทะเบียนเรียนทั้งหมด
     */
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * การลงทะเบียนเรียนปัจจุบัน
     */
    public function currentEnrollment()
    {
        return $this->hasOne(Enrollment::class)
            ->where('status', 'active')
            ->latest('academic_year')
            ->latest('semester');
    }

    /**
     * ห้องเรียน
     */
    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * ชื่อเต็ม
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->prefix}{$this->first_name} {$this->last_name}");
    }

    /**
     * คะแนน
     */
    public function scores()
    {
        return $this->hasMany(Score::class);
    }

}