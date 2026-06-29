<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Schedule;

class Period extends Model
{
    protected $fillable = [
        'name',
        'start_time',
        'end_time'
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}