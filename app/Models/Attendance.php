<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * Mass Assignment
     */
    protected $fillable = [
        'attendance_session_id',
        'student_id',
        'status',
        'check_in_time',
        'remark',
    ];

    /**
     * Type Casting
     */
    protected $casts = [
        'check_in_time' => 'datetime:H:i:s',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Attendance Session
     */
    public function attendanceSession()
    {
        return $this->belongsTo(AttendanceSession::class);
    }

    /**
     * Student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePresent(Builder $query): Builder
    {
        return $query->where('status', 'present');
    }

    public function scopeLate(Builder $query): Builder
    {
        return $query->where('status', 'late');
    }

    public function scopeLeave(Builder $query): Builder
    {
        return $query->where('status', 'leave');
    }

    public function scopeAbsent(Builder $query): Builder
    {
        return $query->where('status', 'absent');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getStatusTextAttribute(): string
    {
        return match ($this->status) {
            'present' => 'มา',
            'late'    => 'สาย',
            'leave'   => 'ลา',
            'absent'  => 'ขาด',
            default   => '-',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'present' => 'green',
            'late'    => 'yellow',
            'leave'   => 'blue',
            'absent'  => 'red',
            default   => 'gray',
        };
    }
}