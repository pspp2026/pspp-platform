<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'school_code',

        // ระบบอนุมัติ
        'status',        // pending / approved
        'role',          // admin / teacher / student / staff / director
        'approved_by',   // user_id ของ admin
        'approved_at',   // datetime
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'approved_at'       => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * admin ที่เป็นผู้อนุมัติ user คนนี้
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}