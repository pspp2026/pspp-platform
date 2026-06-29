<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceRecord extends Model
{
    use HasFactory;

    /**
     * ตาราง
     */
    protected $table = 'attendance_records';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'status',
        'remark',
        'recorded_at',
    ];

    /**
     * Casts
     */
    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    /**
     * สถานะการเข้าเรียน
     */
    public const STATUS_PRESENT = 'present';
    public const STATUS_LATE    = 'late';
    public const STATUS_LEAVE   = 'leave';
    public const STATUS_ABSENT  = 'absent';

    /**
     * รายการสถานะทั้งหมด
     */
    public static function statuses(): array
    {
        return [
            self::STATUS_PRESENT => 'มาเรียน',
            self::STATUS_LATE    => 'มาสาย',
            self::STATUS_LEAVE   => 'ลา',
            self::STATUS_ABSENT  => 'ขาด',
        ];
    }

    /**
     * Session ที่เช็กชื่อ
     */
    public function session()
    {
        return $this->belongsTo(AttendanceSession::class, 'attendance_session_id');
    }

    /**
     * นักเรียน
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * ตรวจสอบว่ามาเรียนหรือไม่
     */
    public function isPresent(): bool
    {
        return $this->status === self::STATUS_PRESENT;
    }

    /**
     * ตรวจสอบว่ามาสายหรือไม่
     */
    public function isLate(): bool
    {
        return $this->status === self::STATUS_LATE;
    }

    /**
     * ตรวจสอบว่าลาหรือไม่
     */
    public function isLeave(): bool
    {
        return $this->status === self::STATUS_LEAVE;
    }

    /**
     * ตรวจสอบว่าขาดหรือไม่
     */
    public function isAbsent(): bool
    {
        return $this->status === self::STATUS_ABSENT;
    }
}