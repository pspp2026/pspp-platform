<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ScoreController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;

        $scores = collect();

        if ($student) {
            $scores = $student->scores()
                ->with([
                    'schedule.subject',
                    'schedule.teacher',
                    'schedule.academicTerm',
                    'grade',
                ])
                ->latest()
                ->get();
        }

        return view('student.scores.index', compact(
            'student',
            'scores'
        ));
    }
}