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
|--------------------------------------------------------------------------
| HEALTHCHECK
|--------------------------------------------------------------------------
*/
Route::get('/health', function () {
    return response('OK', 200);
});

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
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

// AJAX filter โรงเรียน
Route::get('/schools/filter', function (Request $request) {

    $zone = $request->zone ?? 6;

    $schools = School::query()
        ->when($zone, function ($q) use ($zone) {
            return $q->where('zone_code', $zone);
        })
        ->when($request->search, function ($q) use ($request) {
            return $q->where('school_name', 'like', "%{$request->search}%");
        })
        ->when($request->province, function ($q) use ($request) {
            return $q->where('province_id', $request->province);
        })
        ->get();

    return response()->json($schools);
});

// หน้าแรก
Route::get('/', function () {
    $provinces = Province::orderBy('name_th', 'asc')->get();
    return view('home', compact('provinces'));
})->name('home');

//สถิติ




// ปฏิทิน
Route::get('/calendar', [EventController::class, 'index'])->name('calendar');

Route::post('/calendar', [EventController::class, 'store'])
    ->middleware(['auth', 'approved', 'role:admin,teacher,staff'])
    ->name('calendar.store');

/*
|--------------------------------------------------------------------------
| GUEST
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
| AUTH
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/pending', function () {
        return view('auth.pending');
    })->name('pending');

    Route::get('/dashboard', function () {

        $user = Auth::user();

        if (!$user) abort(403);

        if ($user->status !== 'approved') {
            return redirect()->route('pending');
        }

        switch ($user->role ?? null) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'teacher':
                return redirect()->route('teacher.dashboard');
            case 'student':
                return redirect()->route('student.dashboard');
            case 'staff':
                return redirect()->route('staff.dashboard');
            case 'director':
                return redirect()->route('director.dashboard');
            default:
                abort(403);
        }

    })->name('dashboard');
});
/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware([
        'auth',
        'approved',
        'role:admin'
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/dashboard',
            [AdminDashboardController::class, 'index']
        )->name('dashboard');


        /*
        |--------------------------------------------------------------------------
        | User Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/users',
            [UserController::class, 'index']
        )->name('users.index');

        Route::get(
            '/users/pending',
            [UserApprovalController::class, 'index']
        )->name('users.pending');

        Route::post(
            '/users/{user}/approve',
            [UserApprovalController::class, 'approve']
        )->name('users.approve');

        Route::post(
            '/users/{user}/reject',
            [UserApprovalController::class, 'reject']
        )->name('users.reject');

        Route::post(
            '/users/approve-bulk',
            [UserApprovalController::class, 'approveBulk']
        )->name('users.approve.bulk');


        /*
        |--------------------------------------------------------------------------
        | Classroom Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'classrooms',
            ClassroomController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Schedule Management
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/schedules',
            [ScheduleController::class, 'index']
        )->name('schedules.index');

        Route::post(
            '/schedules',
            [ScheduleController::class, 'store']
        )->name('schedules.store');

        Route::get(
            '/schedules/copy',
            [ScheduleController::class, 'copyForm']
        )->name('schedules.copy.form');

        Route::post(
            '/schedules/copy',
            [ScheduleController::class, 'copyStore']
        )->name('schedules.copy.store');

        Route::get(
            '/schedules/{schedule}/edit',
            [ScheduleController::class, 'edit']
        )->name('schedules.edit');

        Route::put(
            '/schedules/{schedule}',
            [ScheduleController::class, 'update']
        )->name('schedules.update');

        Route::delete(
            '/schedules/{schedule}',
            [ScheduleController::class, 'destroy']
        )->name('schedules.destroy');

        Route::get(
            '/schedules/{classroom}',
            [ScheduleController::class, 'timetable']
        )->name('schedules.timetable');


        /*
        |--------------------------------------------------------------------------
        | Enrollment Management
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'enrollments',
            EnrollmentController::class
        );


        /*
        |--------------------------------------------------------------------------
        | Student Enrollment
        | จัดนักเรียนเข้าห้องเรียน
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'student-enrollments',
            StudentEnrollmentController::class
        )->only([
            'index',
            'edit',
            'update',
        ]);

    });

/*
|--------------------------------------------------------------------------
| APPROVED USERS
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'approved'])->group(function () {

    // Schools
    Route::middleware('role:admin,staff')->group(function () {
        Route::resource('schools', SchoolController::class)
            ->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    });
    
    /*  Subjects*/ 

    Route::get('/subjects', [SubjectController::class, 'index'])->name('subjects.index');
    Route::get('/subjects/{subject}', [SubjectController::class, 'show'])->name('subjects.show');

    Route::post('/subjects/{subject}/units', [AdminLearningUnitController::class, 'store'])->name('units.store');
    Route::get('/subjects/{subject}/units/create', [AdminLearningUnitController::class, 'create'])->name('units.create');
    Route::get('/subjects/{subject}/units/{unit}', [AdminLearningUnitController::class, 'show'])->name('units.show');
    Route::get('/subjects/{subject}/units/{unit}/edit', [AdminLearningUnitController::class, 'edit'])->name('units.edit');
    Route::put('/subjects/{subject}/units/{unit}', [AdminLearningUnitController::class, 'update'])->name('units.update');
    Route::delete('/subjects/{subject}/units/{unit}', [AdminLearningUnitController::class, 'destroy'])->name('units.destroy');
    Route::get('/subjects/{subject}/units/{unit}/plans/create', [TeacherLearningUnitController::class, 'createPlan'])->name('units.plans.create');
    Route::post('/subjects/{subject}/units/{unit}/plans', [TeacherLearningUnitController::class, 'storePlan'])->name('units.plans.store');
    Route::get('/subjects/{subject}/units/{unit}/plans/{plan}/edit', [TeacherLearningUnitController::class, 'editPlan'])->name('units.plans.edit');
    Route::put('/subjects/{subject}/units/{unit}/plans/{plan}', [TeacherLearningUnitController::class, 'updatePlan'])->name('units.plans.update');
    Route::delete('/subjects/{subject}/units/{unit}/plans/{plan}', [TeacherLearningUnitController::class, 'destroyPlan'])->name('units.plans.destroy');

    });


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

        Route::get(
            '/attendances/{session}',
            [AttendanceController::class, 'show']
        )->name('attendances.show');
      
         });


    /*
    |---------------- Student ----------------
    */
    Route::prefix('student')
        ->name('student.')
        ->middleware('role:student')
        ->group(function () {

            Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');

            Route::get('/profile', [StudentDashboard::class, 'profile'])->name('profile');

            Route::put('/profile', [StudentDashboard::class, 'updateProfile'])->name('profile.update');
        });

    /*
    |---------------- Staff ----------------
    */
    Route::prefix('staff')
        ->name('staff.')
        ->middleware('role:staff')
        ->group(function () {

            Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');
        });

    /*
    |---------------- Director ----------------
    */
    Route::prefix('director')
        ->name('director.')
        ->middleware('role:director')
        ->group(function () {

            Route::get('/dashboard', [DirectorDashboard::class, 'index'])->name('dashboard');
        });

    /*
    |---------------- Lesson Progress ----------------
    */
    Route::post('/lesson/{id}/read', [LessonProgressController::class, 'markRead'])
        ->middleware('role:student');

    /*
    |---------------- Import Temples ----------------
    */
    Route::get('/import-temples', [TempleImportController::class, 'import']);
});