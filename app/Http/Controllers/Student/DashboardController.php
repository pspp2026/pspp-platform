<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\Lesson;
use App\Models\Province;
use App\Models\Schedule;

class DashboardController extends Controller
{
    // =========================
    // 📊 Dashboard
    // =========================
    public function index()
    {
        $lessons = Lesson::all();

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $student = $user->student;

        if (!$student) {
            abort(404, 'ไม่พบข้อมูลนักเรียน');
        }

        // =========================
        // ความก้าวหน้าการเรียน
        // =========================
        $completedLessons = $user
            ->lessonProgress()
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();

        $completed = $user
            ->lessonProgress()
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();

        $percent = $totalLessons > 0
            ? round(($completed / $totalLessons) * 100)
            : 0;

        // =========================
        // การลงทะเบียนปัจจุบัน
        // =========================
        $currentEnrollment = $student->currentEnrollment()
            ->with([
                'classroom',
                'academicTerm',
                'school'
            ])
            ->first();

        // =========================
        // ตารางเรียน
        // =========================
        $schedules = collect();

        if (
            $currentEnrollment &&
            $currentEnrollment->classroom_id &&
            $currentEnrollment->academic_term_id
        ) {

            $schedules = Schedule::with([
                'subject',
                'teacher',
                'classroom',
                'period',
                'academicTerm'
            ])
            ->where('classroom_id', $currentEnrollment->classroom_id)
            ->where('academic_term_id', $currentEnrollment->academic_term_id)
            ->orderBy('day_of_week')
            ->orderBy('period_id')
            ->get();

        // =========================
        // ตารางเรียนรายสัปดาห์
        // =========================

        $timetable = [];

        foreach ($schedules as $schedule) {

            $timetable[$schedule->day_of_week][$schedule->period_id] = $schedule;

        }

        // =========================
        // รายวิชา
        // =========================

        $subjects = $schedules
            ->pluck('subject')
            ->filter()
            ->unique('id')
            ->values();

        // =========================
        // ครูผู้สอน
        // =========================

        $teachers = $schedules
            ->groupBy('teacher_id');



        }

        // =========================
        // รายวิชาที่ลงทะเบียน
        // =========================
        $subjects = $schedules
            ->pluck('subject')
            ->filter()
            ->unique('id')
            ->values();

        // =========================
        // ครูผู้สอน
        // =========================
        $teachers = $schedules
            ->groupBy('teacher_id');

        return view(
            'student.dashboard',
            compact(
                'student',
                'lessons',
                'completedLessons',
                'percent',
                'currentEnrollment',
                'schedules',
                'subjects',
                'teachers',
                'timetable',
                'subjects',
                
            
            )
        );
    }

    // =========================
    // 👤 Profile
    // =========================
    public function profile()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $user->load(
            'student.enrollments',
            'student.school',
            'student.temple'
        );

        $provinces = Province::all();

        return view(
            'student.profile',
            compact('user', 'provinces')
        );
    }

    // =========================
    // 💾 Update Profile
    // =========================
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // =========================
        // Validation
        // =========================
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',

            'student_code' => 'nullable|string|max:50',

            'phone' => 'nullable|string|max:20',

            'address1' => 'nullable|string',
            'address2' => 'nullable|string',

            'province_id' => 'nullable',
            'district_id' => 'nullable',
            'subdistrict_id' => 'nullable',

            'profile_image' => 'nullable|image|max:2048',
            'cropped_image' => 'nullable|string',

            'password' => 'nullable|confirmed|min:6',
        ]);

        // =========================
        // Upload รูป
        // =========================
        if ($request->filled('cropped_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $image = str_replace(
                'data:image/jpeg;base64,',
                '',
                $request->cropped_image
            );

            $image = str_replace(' ', '+', $image);

            $imageName = 'profiles/' . uniqid() . '.jpg';

            Storage::disk('public')->put(
                $imageName,
                base64_decode($image)
            );

            $user->profile_image = $imageName;

        } elseif ($request->hasFile('profile_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request
                ->file('profile_image')
                ->store('profiles', 'public');
        }

        // =========================
        // USERS
        // =========================
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        $user->address1 = $request->address1;
        $user->address2 = $request->address2;

        $user->province_id = $request->province_id;
        $user->district_id = $request->district_id;
        $user->subdistrict_id = $request->subdistrict_id;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // =========================
        // STUDENTS
        // =========================
        if ($user->student) {

            $user->student->update([
                'student_code' => $request->student_code
                    ?? $user->student->student_code,
            ]);
        }

        return back()->with(
            'success',
            'อัปเดตโปรไฟล์สำเร็จ 🎉'
        );
    }
}