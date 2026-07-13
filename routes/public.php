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
use App\Http\Controllers\HomeController;
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
Route::get('/', [HomeController::class, 'index'])
    ->name('home');

//สถิติ




// ปฏิทิน
Route::get('/calendar', [EventController::class, 'index'])->name('calendar');

Route::post('/calendar', [EventController::class, 'store'])
    ->middleware(['auth', 'approved', 'role:admin,teacher,staff'])
    ->name('calendar.store');

    