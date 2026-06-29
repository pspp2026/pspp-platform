<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LearningUnit extends Model
{
    protected $fillable = [
        'subject_id',
        'unit_no',
        'unit_name',
        'hours',
        'description',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function plans()
    {
        return $this->hasMany(LessonPlan::class, 'unit_id');
    }
}