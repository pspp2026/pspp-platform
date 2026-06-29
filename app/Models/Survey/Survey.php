<?php

namespace App\Models\Survey;

use App\Models\School;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Survey extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'title',
        'description',
        'objective',
        'target_type',
        'status',
        'is_public',
        'start_at',
        'end_at',
        'created_by',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'start_at'  => 'datetime',
        'end_at'    => 'datetime',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function sections()
    {
        return $this->hasMany(SurveySection::class);
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }
}