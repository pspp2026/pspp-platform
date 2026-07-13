<?php

// Illuminate
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

// Models
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\School;
use App\Models\Province;

// Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\UserController; // 🔥 เพิ่ม
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\StudentEnrollmentController;
use App\Http\Controllers\Admin\ClassroomController;
use App\Http\Controllers\Admin\ScheduleController;


use App\Http\Controllers\LearningUnitController as AdminLearningUnitController;

use App\Http\Controllers\Teacher\LearningUnitController as TeacherLearningUnitController;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Teacher\ProfileController as TeacherProfile;
use App\Http\Controllers\Teacher\TeacherScheduleController;
use App\Http\Controllers\Teacher\TeacherStudentController;
use App\Http\Controllers\Teacher\AttendanceController;

use App\Http\Controllers\Student\DashboardController as StudentDashboard;

use App\Http\Controllers\Staff\DashboardController as StaffDashboard;

use App\Http\Controllers\Director\DashboardController as DirectorDashboard;


use App\Http\Controllers\SchoolController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TempleImportController;
use App\Http\Controllers\LessonProgressController;



Route::prefix('director')
    ->name('director.')
    ->middleware([
        'auth',
        'approved',
        'role:director',
        'school.admin',
    ])
    ->group(function () {

        Route::get('/dashboard', [DirectorDashboard::class, 'index'])
            ->name('dashboard');


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */

    Route::get('/subjects', [SubjectController::class, 'index'])
        ->name('subjects.index');

    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])
        ->name('subjects.show');

    /*
    |--------------------------------------------------------------------------
    | Classrooms
    |--------------------------------------------------------------------------
    */

    Route::resource('classrooms', ClassroomController::class);

    /*
    |--------------------------------------------------------------------------
    | Students
    |--------------------------------------------------------------------------
    */

    Route::resource('students', StudentController::class)
        ->except('show');

    /*
    |--------------------------------------------------------------------------
    | Enrollments
    |--------------------------------------------------------------------------
    */

    Route::get('/enrollments/import', [EnrollmentController::class, 'import'])
        ->name('enrollments.import');

    Route::post('/enrollments/import', [EnrollmentController::class, 'importStore'])
        ->name('enrollments.import.store');

    Route::get('/enrollments/template', [EnrollmentController::class, 'downloadTemplate'])
        ->name('enrollments.template');

    Route::resource('enrollments', EnrollmentController::class)
        ->except('show');

        Route::get('/schedules', [ScheduleController::class, 'index'])
    ->name('schedules.index');

Route::post('/schedules', [ScheduleController::class, 'store'])
    ->name('schedules.store');

Route::get('/schedules/copy', [ScheduleController::class, 'copyForm'])
    ->name('schedules.copy.form');

Route::post('/schedules/copy', [ScheduleController::class, 'copyStore'])
    ->name('schedules.copy.store');

Route::get('/schedules/{schedule}/edit', [ScheduleController::class, 'edit'])
    ->name('schedules.edit');

Route::put('/schedules/{schedule}', [ScheduleController::class, 'update'])
    ->name('schedules.update');

Route::delete('/schedules/{schedule}', [ScheduleController::class, 'destroy'])
    ->name('schedules.destroy');

Route::get('/schedules/{classroom}', [ScheduleController::class, 'timetable'])
    ->name('schedules.timetable');
 });