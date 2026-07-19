<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * แสดงรายการบทเรียนของนักเรียน
     */
    public function index()
    {
        $lessons = Lesson::orderBy('id')->get();

        $completedLessons = LessonProgress::where('user_id', Auth::id())
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();

        $completed = count($completedLessons);

        $percent = $totalLessons > 0
            ? round(($completed / $totalLessons) * 100)
            : 0;

        return view('student.lessons.index', compact(
            'lessons',
            'completedLessons',
            'percent'
        ));
    }
}