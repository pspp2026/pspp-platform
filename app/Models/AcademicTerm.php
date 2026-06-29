<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AcademicTerm extends Model
{
    protected $fillable = [
        'academic_year',
        'semester',
        'is_active'
    ];

    public function getNameAttribute()
    {
        return $this->academic_year .
               ' / ภาคเรียน ' .
               $this->semester;
    }
}