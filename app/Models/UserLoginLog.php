<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserLoginLog extends Model
{
    /**
     * Table
     */
    protected $table = 'user_login_logs';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'user_id',
        'school_id',
        'role',
        'login_at',
        'logout_at',
        'ip_address',
        'user_agent',
        'session_id',
        'login_success',
        'login_method',
        'device',
    ];

    /**
     * Attribute Casting
     */
    protected $casts = [
        'login_at'      => 'datetime',
        'logout_at'     => 'datetime',
        'login_success' => 'boolean',
    ];

    /**
     * User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * School
     */
    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    /**
     * ตรวจสอบว่ายัง Login อยู่หรือไม่
     */
    public function isOnline(): bool
    {
        return is_null($this->logout_at);
    }

    /**
     * ระยะเวลาการใช้งาน (นาที)
     */
    public function durationInMinutes(): ?int
    {
        if (!$this->logout_at) {
            return null;
        }

        return $this->login_at->diffInMinutes($this->logout_at);
    }

    /**
     * ระยะเวลาการใช้งาน (ข้อความ)
     */
    public function durationForHumans(): ?string
    {
        if (!$this->logout_at) {
            return 'กำลังใช้งาน';
        }

        return $this->login_at->diffForHumans(
            $this->logout_at,
            [
                'parts' => 3,
                'short' => true,
            ]
        );
    }
}