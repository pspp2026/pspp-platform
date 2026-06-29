<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonPlan extends Model
{
    //
    public function unit()
    {
        return $this->belongsTo(LearningUnit::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
