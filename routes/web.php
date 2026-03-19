<?php

use Illuminate\Support\Facades\Route;

// Auth
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// Admin
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// Roles
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Director\DashboardController as DirectorDashboard;

// ⭐ เพิ่มตรงนี้
use App\Http\Controllers\EventController;


/*
|--------------------------------------------------------------------------
| Public Routes (ยังไม่ login ก็เข้าได้)
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

// ⭐ ปฏิทิน (เพิ่มตรงนี้)
Route::get('/calendar', [EventController::class, 'index'])->name('calendar');
Route::post('/calendar', [EventController::class, 'store'])->name('calendar.store');


// Register
Route::get('/register', [RegisterController::class, 'create'])
    ->name('register');

Route::post('/register', [RegisterController::class, 'store'])
    ->name('register.store');

// Login / Logout
Route::get('/login', [LoginController::class, 'showLoginForm'])
    ->name('login');

Route::post('/login', [LoginController::class, 'login'])
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'logout'])
    ->name('logout');

// Pending approval
Route::get('/pending', function () {
    return view('auth.pending');
})->name('pending');


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->as('admin.')
    ->middleware(['admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/users/pending', [UserApprovalController::class, 'index'])
            ->name('users.pending');

        Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])
            ->name('users.approve');
    });


/*
|--------------------------------------------------------------------------
| Approved User Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['approved'])->group(function () {

    Route::prefix('teacher')->as('teacher.')->group(function () {
        Route::get('/dashboard', [TeacherDashboard::class, 'index'])
            ->name('dashboard');
    });

    Route::prefix('student')->as('student.')->group(function () {
        Route::get('/home', [StudentDashboard::class, 'index'])
            ->name('home');
    });

    Route::prefix('staff')->as('staff.')->group(function () {
        Route::get('/dashboard', [StaffDashboard::class, 'index'])
            ->name('dashboard');
    });

    Route::prefix('director')->as('director.')->group(function () {
        Route::get('/dashboard', [DirectorDashboard::class, 'index'])
            ->name('dashboard');
    });
});