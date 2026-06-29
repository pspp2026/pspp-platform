<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Staff;
use App\Models\Director;
use App\Models\Temple;
use App\Models\LessonProgress;
use App\Models\Province;
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\School;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

 /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    public const ROLE_SUPER_ADMIN = 'superadmin';
    public const ROLE_ADMIN       = 'admin';
    public const ROLE_DIRECTOR    = 'director';
    public const ROLE_TEACHER     = 'teacher';
    public const ROLE_STUDENT     = 'student';
    public const ROLE_STAFF       = 'staff';

    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    

    /*
    |--------------------------------------------------------------------------
    | 🔥 Fillable
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id',
        'status',
        'phone',
        'profile_image',
        'address1',
        'address2',
        'province_id',
        'district_id',
        'subdistrict_id',
        'zipcode',
        'approved_by',
        'approved_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔒 Hidden
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔁 Casts
    |--------------------------------------------------------------------------
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | 🔗 RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 🎓 User → Student (1:1)
    public function student()
    {
        return $this->hasOne(Student::class);
    }

    // 🏯 User → Temple (1:1)
    public function temple()
    {
        return $this->hasOne(Temple::class);
    }

    // 📚 ความคืบหน้าการเรียน
    public function lessonProgress()
    {
        return $this->hasMany(\App\Models\LessonProgress::class);
    }

    // 👨‍🏫 Teacher
    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    // 🧑‍💼 Staff
    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    // 👑 Director
    public function director()
    {
        return $this->hasOne(Director::class);
    }

      // 👑 School
        public function school()
    {
        return $this->belongsTo(School::class);
    }

     // 🏞️ Province
     public function province()
     {
         return $this->belongsTo(Province::class);
     }

     // 🏞️ District
     public function district()
     {
         return $this->belongsTo(District::class);
     }

     // 🏞️ Subdistrict
     public function subdistrict()
     {
         return $this->belongsTo(Subdistrict::class);
     }


    /*
    |--------------------------------------------------------------------------
    | 🎯 Display Name (รวมชื่อทุก role)
    |--------------------------------------------------------------------------
    */
    public function getDisplayNameAttribute()
    {
        // 👨‍🎓 Student
        if ($this->role === 'student' && $this->student) {
            return $this->student->prefix
                . $this->student->first_name . ' '
                . $this->student->last_name;
        }

        // 👨‍🏫 Teacher
        if ($this->role === 'teacher' && $this->teacher) {
            return $this->teacher->prefix
                . $this->teacher->first_name . ' '
                . $this->teacher->last_name;
        }

        // 🧑‍💼 Staff
        if ($this->role === 'staff' && $this->staff) {
            return $this->staff->prefix
                . $this->staff->first_name . ' '
                . $this->staff->last_name;
        }

        // 👑 Director
        if ($this->role === 'director' && $this->director) {
            return $this->director->prefix
                . $this->director->first_name . ' '
                . $this->director->last_name;
        }

        // 🔁 fallback
        return $this->name ?? 'User';
    }
//*schedules
    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'teacher_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Role Helpers
    |--------------------------------------------------------------------------
    */

    /**
     * Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    /**
     * School Admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Director
     */
    public function isDirector(): bool
    {
        return $this->role === self::ROLE_DIRECTOR;
    }

    /**
     * Teacher
     */
    public function isTeacher(): bool
    {
        return $this->role === self::ROLE_TEACHER;
    }

    /**
     * Student
     */
    public function isStudent(): bool
    {
        return $this->role === self::ROLE_STUDENT;
    }

    /**
     * Staff
     */
    public function isStaff(): bool
    {
        return $this->role === self::ROLE_STAFF;
    }

    /**
     * ผู้ใช้งานระดับโรงเรียน
     */
    public function isSchoolUser(): bool
    {
        return in_array($this->role, [
            self::ROLE_ADMIN,
            self::ROLE_DIRECTOR,
            self::ROLE_TEACHER,
            self::ROLE_STAFF,
            self::ROLE_STUDENT,
        ]);
    }

    /**
     * ตรวจสอบว่าอยู่โรงเรียนเดียวกันหรือไม่
     */
    public function belongsToSchool(int|string|null $schoolId): bool
    {
        if ($schoolId === null) {
            return false;
        }

        return (int) $this->school_id === (int) $schoolId;
    }

    /**
     * Super Admin หรืออยู่โรงเรียนเดียวกัน
     */
    public function canAccessSchool(int|string|null $schoolId): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->belongsToSchool($schoolId);
    }

    /**
     * ตรวจสอบหลาย Role พร้อมกัน
     *
     * ตัวอย่าง:
     * $user->hasRole('teacher')
     * $user->hasRole(['admin','director'])
     */
    public function hasRole(string|array $roles): bool
    {
        return in_array($this->role, (array) $roles, true);
    }

}