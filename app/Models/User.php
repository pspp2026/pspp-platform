<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

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
    // 🏯 User → Temple (1:1
    public function temple()
    {
        return $this->hasOne(Temple::class);
    }   
    // 📚 ความคืบหน้าการเรียน
    public function lessonProgress()
    {
        return $this->hasMany(\App\Models\LessonProgress::class);
    }

}