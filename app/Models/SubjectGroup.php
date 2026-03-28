<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubjectGroup extends Model
{
    protected $table = 'subject_groups';

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];
}