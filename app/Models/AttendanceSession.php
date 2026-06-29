<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    /**
     * Session Status
     */
    public const STATUS_DRAFT = 'draft';
    public const STATUS_COMPLETED = 'completed';

    /**
     * Mass Assignment
     */
    protected $fillable = [
        'school_id',
        'academic_term_id',
        'schedule_id',
        'classroom_id',
        'subject_id',
        'teacher_id',
        'period_id',
        'attendance_date',
        'topic',
        'note',
        'status',
    ];

    /**
     * Type Casting
     */
    protected $casts = [
        'attendance_date' => 'date',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function period()
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * ผลการเช็กชื่อทั้งหมดใน Session
     */
    public function records()
    {
        return $this->hasMany(
            AttendanceRecord::class,
            'attendance_session_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_COMPLETED
        );
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where(
            'status',
            self::STATUS_DRAFT
        );
    }

    public function scopeByTerm(
        Builder $query,
        int $termId
    ): Builder {
        return $query->where(
            'academic_term_id',
            $termId
        );
    }

    public function scopeByTeacher(
        Builder $query,
        int $teacherId
    ): Builder {
        return $query->where(
            'teacher_id',
            $teacherId
        );
    }

    public function scopeByClassroom(
        Builder $query,
        int $classroomId
    ): Builder {
        return $query->where(
            'classroom_id',
            $classroomId
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {

            self::STATUS_DRAFT => 'กำลังเช็กชื่อ',

            self::STATUS_COMPLETED => 'เช็กชื่อแล้ว',

            default => '-',

        };
    }

    public function getAttendanceCountAttribute(): int
    {
        return $this->records()->count();
    }

    /**
     * ตรวจสอบว่า Session ปิดแล้วหรือยัง
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * ตรวจสอบว่า Session ยังเปิดอยู่
     */
    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }
}