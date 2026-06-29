<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'label',
        'value',
        'sort_order',
    ];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class);
    }
}