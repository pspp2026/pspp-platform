<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    protected $table = 'directors';

    protected $fillable = [
        'user_id',
        'director_code',
        'prefix',
        'first_name',
        'last_name',
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
}