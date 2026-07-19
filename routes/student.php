<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\ScoreController;

Route::middleware([
    'auth',
    'approved',
    'role:student',
    'track.online',
])
->prefix('student')
->name('student.')
->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Lessons
    |--------------------------------------------------------------------------
    */
    Route::get('/lessons', [LessonController::class, 'index'])
        ->name('lessons');

    /*
    |--------------------------------------------------------------------------
    | Assignments
    |--------------------------------------------------------------------------
    */
    Route::get('/assignments', [DashboardController::class, 'assignments'])
        ->name('assignments');

    /*
    |--------------------------------------------------------------------------
    | Scores
    |--------------------------------------------------------------------------
    */
    Route::get('/scores', [ScoreController::class, 'index'])
        ->name('scores');

    /*
    |--------------------------------------------------------------------------
    | Schedule
    |--------------------------------------------------------------------------
    */
    Route::get('/schedule', [DashboardController::class, 'schedule'])
        ->name('schedule');

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [DashboardController::class, 'profile'])
        ->name('profile');

    Route::put('/profile', [DashboardController::class, 'updateProfile'])
        ->name('profile.update');
});