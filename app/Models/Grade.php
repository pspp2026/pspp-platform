<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    /**
     * --------------------------------------------------------------------------
     * Mass Assignment
     * --------------------------------------------------------------------------
     */
    protected $fillable = [
        'score_id',
        'grade',
        'grade_point',
        'passed',
        'calculated_at',
        'remark',
    ];

    /**
     * --------------------------------------------------------------------------
     * Casts
     * --------------------------------------------------------------------------
     */
    protected $casts = [
        'grade_point'   => 'decimal:2',
        'passed'        => 'boolean',
        'calculated_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * คะแนน
     */
    public function score()
    {
        return $this->belongsTo(Score::class);
    }
}