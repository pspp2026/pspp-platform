<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\Period;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\User;

class ScheduleController extends Controller
{
    public function index(Classroom $classroom)
    {
        $periods = Period::orderBy('id')->get();

        $schedules = Schedule::with([
            'subject',
            'teacher',
            'period'
        ])
        ->where('classroom_id', $classroom->id)
        ->get();

        return view(
            'admin.schedules.index',
            compact(
                'classroom',
                'periods',
                'schedules'
            )
        );
    }
}