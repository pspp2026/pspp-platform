<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Student\DashboardController;
use App\Http\Controllers\Student\LessonController;
use App\Http\Controllers\Student\ScoreController;

use App\Models\Temple;

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


    /*
    |--------------------------------------------------------------------------
    | Temple Selection
    |--------------------------------------------------------------------------
    |
    | ดึงข้อมูลจากตาราง temples โดยตรง
    |
    | province
    | district
    | subdistrict
    |
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | 1. โหลดอำเภอจากจังหวัด
    |--------------------------------------------------------------------------
    |
    | ตัวอย่าง:
    | /student/temple-districts/แพร่
    |
    */
    Route::get(
        '/temple-districts/{province}',
        function ($province) {

            return Temple::query()
                ->where('province', $province)
                ->whereNotNull('district')
                ->where('district', '!=', '')
                ->select('district')
                ->distinct()
                ->orderBy('district')
                ->pluck('district');
        }
    )->name('temple.districts');


    /*
    |--------------------------------------------------------------------------
    | 2. โหลดตำบลจากจังหวัด + อำเภอ
    |--------------------------------------------------------------------------
    |
    | ตัวอย่าง:
    | /student/temple-subdistricts/แพร่/เมืองแพร่
    |
    */
    Route::get(
        '/temple-subdistricts/{province}/{district}',
        function ($province, $district) {

            return Temple::query()
                ->where('province', $province)
                ->where('district', $district)
                ->whereNotNull('subdistrict')
                ->where('subdistrict', '!=', '')
                ->select('subdistrict')
                ->distinct()
                ->orderBy('subdistrict')
                ->pluck('subdistrict');
        }
    )->name('temple.subdistricts');


    /*
    |--------------------------------------------------------------------------
    | 3. โหลดรายชื่อวัดจากจังหวัด + อำเภอ + ตำบล
    |--------------------------------------------------------------------------
    |
    | ตัวอย่าง:
    | /student/temples/แพร่/เมืองแพร่/ในเวียง
    |
    */
    Route::get(
        '/temples/{province}/{district}/{subdistrict}',
        function ($province, $district, $subdistrict) {

            return Temple::query()
                ->where('province', $province)
                ->where('district', $district)
                ->where('subdistrict', $subdistrict)
                ->orderBy('temple_name')
                ->get([
                    'id',
                    'temple_name',
                    'province',
                    'district',
                    'subdistrict',
                ]);
        }
    )->name('temples.list');

});