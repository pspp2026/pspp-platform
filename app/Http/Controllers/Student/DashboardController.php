<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Models\Lesson;
use App\Models\Province;

class DashboardController extends Controller
{
    // =========================
    // 📊 Dashboard
    // =========================
    public function index()
    {
        $lessons = Lesson::all();

        $completedLessons = Auth::user()
            ->lessonProgress()
            ->pluck('lesson_id')
            ->toArray();

        $totalLessons = $lessons->count();

        $completed = Auth::user()
            ->lessonProgress()
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
        $user = auth()->user()->load(
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

        // =========================
        // ✅ VALIDATION
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
        // 🖼️ Upload / Crop Image
        // =========================
        if ($request->filled('cropped_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $image = $request->cropped_image;
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $imageName = 'profiles/' . uniqid() . '.jpg';

            Storage::disk('public')->put($imageName, base64_decode($image));

            $user->profile_image = $imageName;

        } elseif ($request->hasFile('profile_image')) {

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $path = $request->file('profile_image')->store('profiles', 'public');
            $user->profile_image = $path;
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

        // 🔐 password
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // =========================
        // 🎓 STUDENTS TABLE
        // =========================
        if ($user->student) {
            $user->student->update([
                'student_code' => $request->student_code ?? $user->student->student_code,
            ]);
        }

        return back()->with('success', 'อัปเดตโปรไฟล์สำเร็จ 🎉');
    }
}