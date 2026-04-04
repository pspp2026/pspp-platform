<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    // 🔥 กำหนด field ที่อนุญาตให้ insert/update
    protected $fillable = [
        'user_id',
        'school_id',
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
    | 🔗 RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 👤 นักเรียนเป็นของ User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 🏫 โรงเรียน
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // 🏯 วัด
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    // 🎓 การลงทะเบียนเรียน (ชั้น / เทอม / ปี)
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    /*
    |--------------------------------------------------------------------------
    | 🎯 ACCESSORS (ตัวช่วยแสดงผล)
    |--------------------------------------------------------------------------
    */

    // 👤 ชื่อเต็ม (เอาไปใช้ใน Blade ได้เลย)
    public function getFullNameAttribute()
    {
        return trim("{$this->prefix}{$this->first_name} {$this->last_name}");
    }
}