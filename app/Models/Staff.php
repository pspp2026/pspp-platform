<?php

namespace App\Models;

use App\Models\User;
use App\Models\School;
use App\Models\Temple;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'staff_code',
        'prefix',
        'first_name',
        'last_name',
        'position',
        'department',
        'school_id',
        'temple_id',
        'is_monk',
        'status',
    ];

    /**
     * ความสัมพันธ์กับ users
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ความสัมพันธ์กับ temple (ถ้ามี)
     */
    public function temple()
    {
        return $this->belongsTo(Temple::class);
    }

    /**
     * helper: ชื่อเต็ม
     */
    public function getFullNameAttribute()
    {
        return trim("{$this->prefix}{$this->first_name} {$this->last_name}");
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}