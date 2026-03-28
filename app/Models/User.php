<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass Assignable
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'external_code',
        'user_code',

        // 👤 ข้อมูลส่วนตัว
        'id_card',
        'name_th',
        'name_en',
        'phone',
        'profile_image',

        // 🏫 โรงเรียน
        'school_id',
        'school_code',
        'school_name',

        // 📍 ที่อยู่
        'address1',
        'address2',
        'province_id',
        'district_id',
        'subdistrict_id',
        'zipcode', // ✅ ใช้ zipcode (ไม่มี _)

        // 🔐 ระบบอนุมัติ
        'status',
        'role',
        'approved_by',
        'approved_at',
    ];

    /**
     * Hidden fields
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at'       => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =========================
    // 🔗 RELATIONS
    // =========================

    /**
     * ผู้อนุมัติ (admin)
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * โรงเรียน
     */
    public function school()
    {
        return $this->belongsTo(\App\Models\School::class);        
    }

    /**
     * จังหวัด
     */
    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class);
    }

    /**
     * อำเภอ
     */
    public function district()
    {
        return $this->belongsTo(\App\Models\District::class);
    }

    /**
     * ตำบล
     */
    public function subdistrict()
    {
        return $this->belongsTo(\App\Models\Subdistrict::class);
    }

    // =========================
    // 🎯 Helper (ใช้บ่อย)
    // =========================

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isTeacher()
    {
        return $this->role === 'teacher';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    public function isDirector()
    {
        return $this->role === 'director';
    }
}