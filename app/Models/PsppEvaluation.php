<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PsppEvaluation extends Model
{
    protected $table = 'pspp_evaluations';

    protected $fillable = [

        'user_id',

        'school_id',
        'school_name',

        'role',

        'class_level',

        'student_code',

        'answer1',
        'answer2',
        'answer3',
        'answer4',
        'answer5',
        'answer6',
        'answer7',
        'answer8',
        'answer9',
        'answer10',
        'answer11',
        'answer12',
        'answer13',
        'answer14',
        'answer15',
        'answer16',
        'answer17',
        'answer18',
        'answer19',
        'answer20',
        'answer21',
        'answer22',
        'answer23',

        'suggestion',

        'submitted_at',

    ];

    protected $casts = [

        'submitted_at' => 'datetime',

    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}