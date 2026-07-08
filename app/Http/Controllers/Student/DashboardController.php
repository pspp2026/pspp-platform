<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Province;
use App\Models\User;

class DashboardController extends Controller
{
    // =========================
    // 📊 Dashboard
    // =========================
    public function index()
    {
        $lessons = Lesson::all();

        $completedLessons = LessonProgress::where('user_id', Auth::id())
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();

        $completed = LessonProgress::where('user_id', Auth::id())
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();

        $percent = $totalLessons > 0
            ? round(($completed / $totalLessons) * 100)
            : 0;

        return view('student.dashboard', compact(
            'lessons',
            'completedLessons',
            'percent'
        ));
    }

    // =========================
    // 👤 Profile
    // =========================
    public function profile()
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }

        $user->load(
            'student.enrollments',
            'student.school',
            'student.temple'
        );

        $provinces = Province::all();

        return view('student.profile', compact('user', 'provinces'));
    }

    // =========================
    // 💾 Update Profile
    // =========================
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        if (! $user instanceof User) {
            abort(403);
        }
        $user->load('student.school');

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

        /*
        |--------------------------------------------------------------------------
        | 🖼️ ตั้งชื่อรูปมาตรฐาน
        |--------------------------------------------------------------------------
        | STD-PK-S1001_20260708_214500.jpg
        | ROLE-SCHOOL_CODE-STUDENT_CODE_YYYYMMDD_HHMMSS.extension
        */
        $student = $user->student;

        $role = 'STD';
        $schoolCode = $student?->school?->school_code ?? 'UNKNOWN';
        $studentCode = $student?->student_code ?? ('USER-' . $user->id);

        // ป้องกันอักขระพิเศษ/ช่องว่างในชื่อไฟล์
        $schoolCode = preg_replace('/[^A-Za-z0-9_-]/', '', $schoolCode);
        $studentCode = preg_replace('/[^A-Za-z0-9_-]/', '', $studentCode);

        $timestamp = now()->format('Ymd_His');

        // =========================
        // 🖼️ Upload / Crop Image
        // =========================
        if ($request->filled('cropped_image')) {
            $image = $request->cropped_image;

            // รองรับ data URL ของ jpeg, png, webp
            if (!preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $image, $matches)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'cropped_image' => 'รูปภาพที่ตัดไม่อยู่ในรูปแบบที่รองรับ',
                    ]);
            }

            $extension = strtolower($matches[1]);
            $extension = $extension === 'jpeg' ? 'jpg' : $extension;

            $imageData = preg_replace(
                '/^data:image\/(jpeg|jpg|png|webp);base64,/',
                '',
                $image
            );

            $imageData = str_replace(' ', '+', $imageData);
            $decodedImage = base64_decode($imageData, true);

            if ($decodedImage === false) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'cropped_image' => 'ไม่สามารถอ่านข้อมูลรูปภาพได้',
                    ]);
            }

            $fileName = sprintf(
                '%s-%s-%s_%s.%s',
                $role,
                $schoolCode,
                $studentCode,
                $timestamp,
                $extension
            );

            $imagePath = 'profiles/' . $fileName;

            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            Storage::disk('public')->put($imagePath, $decodedImage);

            $user->profile_image = $imagePath;

        } elseif ($request->hasFile('profile_image')) {
            $extension = strtolower(
                $request->file('profile_image')->getClientOriginalExtension()
            );

            // ป้องกันกรณี browser ส่ง extension แปลกมา
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];
            if (!in_array($extension, $allowedExtensions, true)) {
                $extension = 'jpg';
            }

            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            $fileName = sprintf(
                '%s-%s-%s_%s.%s',
                $role,
                $schoolCode,
                $studentCode,
                $timestamp,
                $extension
            );

            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')->storeAs(
                'profiles',
                $fileName,
                'public'
            );
        }

        // =========================
        // 👤 USERS TABLE
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
        // 🎓 STUDENTS TABLE
        // =========================
        if ($student) {
            $student->update([
                'student_code' => $request->student_code ?? $student->student_code,
            ]);
        }

        return back()->with('success', 'อัปเดตโปรไฟล์สำเร็จ 🎉');
    }
}