<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// 🔥 Models
use App\Models\District;
use App\Models\Subdistrict;
use App\Models\School;

// 🔥 Auth Controllers
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// 🔥 Admin
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;

// 🔥 Roles
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Director\DashboardController as DirectorDashboard;

// 🔥 Feature
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubjectController;


/*
|--------------------------------------------------------------------------
| Public Routes (🔥 แก้ตรงนี้)
|--------------------------------------------------------------------------
*/

// ✅ หน้าแรก (ไม่ต้อง login)
Route::get('/', function () {

    $schools = School::with('province')
        ->where('zone_code', 6)
        ->get();

    return view('home', compact('schools'));

})->name('home');


Route::get('/calendar', [EventController::class, 'index'])->name('calendar');

Route::post('/calendar', [EventController::class, 'store'])
    ->middleware(['auth', 'approved', 'role:admin,teacher,staff'])
    ->name('calendar.store');


/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.store');
});


/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::get('/pending', fn () => view('auth.pending'))
    ->middleware('auth')
    ->name('pending');

Route::middleware('auth')->get('/dashboard', function () {

    $user = Auth::user();

    if ($user->status !== 'approved') {
        return redirect()->route('pending');
    }

    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'teacher' => redirect()->route('teacher.dashboard'),
        'student' => redirect()->route('student.home'),
        'staff' => redirect()->route('staff.dashboard'),
        'director' => redirect()->route('director.dashboard'),
        default => abort(403),
    };

})->name('dashboard');


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->as('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])
            ->name('dashboard');

        Route::get('/users/pending', [UserApprovalController::class, 'index'])
            ->name('users.pending');

        Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])
            ->name('users.approve');

        Route::post('/users/{user}/reject', [UserApprovalController::class, 'reject'])
            ->name('users.reject');
    });


/*
|--------------------------------------------------------------------------
| Approved Users
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'approved'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Schools
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,staff')->group(function () {

        Route::resource('schools', SchoolController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

        // จังหวัด → อำเภอ
        Route::get('/districts/{province_id}', function ($province_id) {
            return response()->json(
                District::where('province_id', $province_id)->get()
            );
        })->name('districts');

        // อำเภอ → ตำบล
        Route::get('/subdistricts/{district_id}', function ($district_id) {
            return response()->json(
                Subdistrict::where('district_id', $district_id)->get()
            );
        })->name('subdistricts');
    });


    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */
    Route::get('/subjects', [SubjectController::class, 'index'])
        ->name('subjects.index');


    /*
    |--------------------------------------------------------------------------
    | Teacher
    |--------------------------------------------------------------------------
    */
    Route::prefix('teacher')
        ->as('teacher.')
        ->middleware('role:teacher')
        ->group(function () {

            Route::get('/dashboard', [TeacherDashboard::class, 'index'])
                ->name('dashboard');

            Route::get('/profile', [TeacherDashboard::class, 'profile'])
                ->name('profile');

            Route::put('/profile', [TeacherDashboard::class, 'updateProfile'])
                ->name('profile.update');
        });


    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')
        ->as('student.')
        ->middleware('role:student')
        ->group(function () {

            Route::get('/home', [StudentDashboard::class, 'index'])
                ->name('home');
        });


    /*
    |--------------------------------------------------------------------------
    | Staff
    |--------------------------------------------------------------------------
    */
    Route::prefix('staff')
        ->as('staff.')
        ->middleware('role:staff')
        ->group(function () {

            Route::get('/dashboard', [StaffDashboard::class, 'index'])
                ->name('dashboard');
        });


    /*
    |--------------------------------------------------------------------------
    | Director
    |--------------------------------------------------------------------------
    */
    Route::prefix('director')
        ->as('director.')
        ->middleware('role:director')
        ->group(function () {

            Route::get('/dashboard', [DirectorDashboard::class, 'index'])
                ->name('dashboard');
        });

});