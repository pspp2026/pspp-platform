<?php

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
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Teacher\DashboardController as TeacherDashboard;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Director\DashboardController as DirectorDashboard;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\SubjectController;

/*
|--------------------------------------------------------------------------
| 🔥 HEALTHCHECK
|--------------------------------------------------------------------------
*/
Route::get('/health', fn () => response('OK', 200));

/*
|--------------------------------------------------------------------------
| 🌍 PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

// จังหวัด → อำเภอ
Route::get('/districts/{province_id}', function ($province_id) {
    return response()->json(
        District::where('province_id', $province_id)->get()
    );
});

// อำเภอ → ตำบล
Route::get('/subdistricts/{district_id}', function ($district_id) {
    return response()->json(
        Subdistrict::where('district_id', $district_id)->get()
    );
});

// 🔥 AJAX filter โรงเรียน (สำคัญ: ต้อง public)
Route::get('/schools/filter', function (Request $request) {

    $zone = $request->zone ?? 6;
    $search = $request->search;
    $province = $request->province;

    $schools = School::query()
        ->when($zone, fn($q) => $q->where('zone_code', $zone))
        ->when($search, fn($q) => $q->where('school_name', 'like', "%$search%"))
        ->when($province, fn($q) => $q->where('province_id', $province))
        ->get();

    return response()->json($schools);
});

// 🏠 หน้าแรก (ไม่ต้อง login)
Route::get('/', function () {

    $provinces = Province::orderBy('name_th', 'asc')->get();

    return view('home', compact('provinces'));
})->name('home');

// 📅 ปฏิทิน
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
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/pending', fn () => view('auth.pending'))->name('pending');

    Route::get('/dashboard', function () {

        $user = Auth::user();

        if (!$user) abort(403);

        if ($user->status !== 'approved') {
            return redirect()->route('pending');
        }

        return match ($user->role ?? null) {
            'admin'    => redirect()->route('admin.dashboard'),
            'teacher'  => redirect()->route('teacher.dashboard'),
            'student'  => redirect()->route('student.home'),
            'staff'    => redirect()->route('staff.dashboard'),
            'director' => redirect()->route('director.dashboard'),
            default    => abort(403),
        };

    })->name('dashboard');
});


/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

        Route::get('/users/pending', [UserApprovalController::class, 'index'])->name('users.pending');

        Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');

        Route::post('/users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');
    });


/*
|--------------------------------------------------------------------------
| Approved Users
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'approved'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Schools (admin + staff เท่านั้น)
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin,staff')->group(function () {

        Route::resource('schools', SchoolController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);

    });

    /*
    |--------------------------------------------------------------------------
    | Subjects
    |--------------------------------------------------------------------------
    */
    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');


    /*
    |--------------------------------------------------------------------------
    | Teacher
    |--------------------------------------------------------------------------
    */
    Route::prefix('teacher')
        ->name('teacher.')
        ->middleware('role:teacher')
        ->group(function () {

            Route::get('/dashboard', [TeacherDashboard::class, 'index'])->name('dashboard');

            Route::get('/profile', [TeacherDashboard::class, 'profile'])->name('profile');

            Route::put('/profile', [TeacherDashboard::class, 'updateProfile'])->name('profile.update');
        });


    /*
    |--------------------------------------------------------------------------
    | Student
    |--------------------------------------------------------------------------
    */
    Route::prefix('student')
        ->name('student.')
        ->middleware('role:student')
        ->group(function () {

            Route::get('/home', [StudentDashboard::class, 'index'])->name('home');
        });


    /*
    |--------------------------------------------------------------------------
    | Staff
    |--------------------------------------------------------------------------
    */
    Route::prefix('staff')
        ->name('staff.')
        ->middleware('role:staff')
        ->group(function () {

            Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');
        });


    /*
    |--------------------------------------------------------------------------
    | Director
    |--------------------------------------------------------------------------
    */
    Route::prefix('director')
        ->name('director.')
        ->middleware('role:director')
        ->group(function () {

            Route::get('/dashboard', [DirectorDashboard::class, 'index'])->name('dashboard');
        });

});