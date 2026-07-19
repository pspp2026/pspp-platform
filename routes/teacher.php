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
use App\Http\Controllers\Teacher\TeacherScoreController;
use App\Http\Controllers\Teacher\TeacherGradeController;

use App\Http\Controllers\Student\DashboardController as StudentDashboard;

use App\Http\Controllers\Staff\DashboardController as StaffDashboard;

use App\Http\Controllers\Director\DashboardController as DirectorDashboard;

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TempleImportController;
use App\Http\Controllers\LessonProgressController;


/*
|--------------------------------------------------------------------------
| TEACHER
|--------------------------------------------------------------------------
*/

Route::prefix('teacher')
    ->name('teacher.')
    ->middleware([
        'auth',
        'approved',
        'role:teacher',
        'track.online',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [TeacherDashboard::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | Profile
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [TeacherProfile::class, 'profile']
        )->name('profile');

        Route::put(
            '/profile',
            [TeacherProfile::class, 'updateProfile']
        )->name('profile.update');


        /*
        |--------------------------------------------------------------------------
        | Timetable
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/timetable',
            [TeacherScheduleController::class, 'index']
        )->name('timetable');


        /*
        |--------------------------------------------------------------------------
        | Student List
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/students/{schedule}',
            [TeacherStudentController::class, 'index']
        )->name('students.index');


        /*
        |--------------------------------------------------------------------------
        | My Subjects
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/subjects',
            [TeacherDashboard::class, 'mySubjects']
        )->name('subjects');


        /*
        |--------------------------------------------------------------------------
        | Subject Assignment
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/manage',
            [\App\Http\Controllers\Teacher\SubjectAssignmentController::class, 'index']
        )->name('subjects.manage');

        Route::post(
            '/manage',
            [\App\Http\Controllers\Teacher\SubjectAssignmentController::class, 'update']
        )->name('subjects.manage.update');


        /*
        |--------------------------------------------------------------------------
        | Learning Units
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/subjects/{subject}/units',
            [TeacherLearningUnitController::class, 'index']
        )->name('units.index');

        Route::get(
            '/subjects/{subject}/units/create',
            [TeacherLearningUnitController::class, 'create']
        )->name('units.create');

        Route::post(
            '/subjects/{subject}/units',
            [TeacherLearningUnitController::class, 'store']
        )->name('units.store');

        Route::get(
            '/units/{unit}/edit',
            [TeacherLearningUnitController::class, 'edit']
        )->name('units.edit');

        Route::put(
            '/units/{unit}',
            [TeacherLearningUnitController::class, 'update']
        )->name('units.update');

        Route::delete(
            '/units/{unit}',
            [TeacherLearningUnitController::class, 'destroy']
        )->name('units.destroy');

           /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/attendances',
                [AttendanceController::class, 'index']
            )->name('attendances.index');

            Route::get(
                '/attendances/take',
                [AttendanceController::class, 'takeAttendance']
            )->name('attendances.take');

            Route::post(
                '/attendances/store',
                [AttendanceController::class, 'storeAttendance']
            )->name('attendances.store');

            Route::get(
                '/attendances/history',
                [AttendanceController::class, 'history']
            )->name('attendances.history');

            Route::get(
                '/attendances/report',
                [AttendanceController::class, 'report']
            )->name('attendances.report');

            /*
            |--------------------------------------------------------------------------
            | Edit Attendance
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/attendances/{session}/edit',
                [AttendanceController::class, 'edit']
            )->name('attendances.edit');

            Route::put(
                '/attendances/{session}',
                [AttendanceController::class, 'update']
            )->name('attendances.update');

            /*
            |--------------------------------------------------------------------------
            | Show Attendance
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/attendances/{session}',
                [AttendanceController::class, 'show']
            )->name('attendances.show');
        
            /*
            |--------------------------------------------------------------------------
            | Score
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/scores',
                [TeacherScoreController::class, 'index']
            )->name('scores.index');

            Route::get(
                '/scores/{schedule}',
                [TeacherScoreController::class, 'show']
            )->name('scores.show');

            Route::put(
                '/scores/{schedule}',
                [TeacherScoreController::class, 'update']
            )->name('scores.update');

            /*
            |--------------------------------------------------------------------------
            | Grade
            |--------------------------------------------------------------------------
            */

            Route::get(
                '/grades',
                [TeacherGradeController::class, 'index']
            )->name('grades.index');

            Route::get(
                '/grades/{schedule}',
                [TeacherGradeController::class, 'show']
            )->name('grades.show');

            Route::post(
                '/grades/{schedule}/calculate',
                [TeacherGradeController::class, 'calculate']
            )->name('grades.calculate');

    });