<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    protected $fillable = [
        'user_id',
        'lesson_id',
        'read_done',
        'quiz_done',
        'quiz_score',
        'reflection_done',
        'time_spent',
        'progress_percent',
        'completed'
    ];
}