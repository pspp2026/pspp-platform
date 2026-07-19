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

use App\Http\Controllers\Student\DashboardController as StudentDashboard;

use App\Http\Controllers\Staff\DashboardController as StaffDashboard;

use App\Http\Controllers\Director\DashboardController as DirectorDashboard;

use App\Http\Controllers\SchoolController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TempleImportController;
use App\Http\Controllers\LessonProgressController;


    /*
    |---------------- Student ----------------
    */
    Route::prefix('student')
        ->name('student.')
        ->middleware([
            'auth',
            'approved',
            'role:student',
            'track.online',
        ])
        ->group(function () {

            Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

            Route::get('/profile', [StudentDashboard::class, 'profile'])->name('profile');

            Route::put('/profile', [StudentDashboard::class, 'updateProfile'])->name('profile.update');
        });
