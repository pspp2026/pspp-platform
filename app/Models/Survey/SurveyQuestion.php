<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'section_id',
        'question',
        'description',
        'question_type',
        'required',
        'sort_order',
        'settings',
    ];

    protected $casts = [
        'required' => 'boolean',
        'settings' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(SurveySection::class);
    }

    public function options()
    {
        return $this->hasMany(SurveyOption::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'question_id');
    }
}