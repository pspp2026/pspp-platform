<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Province;
use App\Models\User;

use App\Services\UserCodeService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * หน้า Dashboard นักเรียน
     */
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

    /**
     * หน้าโปรไฟล์นักเรียน
     */
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

        $provinces = Province::query()
            ->orderBy('name_th')
            ->get();

        return view('student.profile', compact('user', 'provinces'));
    }

    /**
     * บันทึกข้อมูลโปรไฟล์นักเรียน
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        $user->load('student.school');

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],

            'external_code' => ['nullable', 'string', 'max:50'],

            'phone' => ['nullable', 'string', 'max:20'],
            'address1' => ['nullable', 'string'],
            'address2' => ['nullable', 'string'],

            'province_id' => ['nullable'],
            'district_id' => ['nullable'],
            'subdistrict_id' => ['nullable'],
          

            'profile_image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cropped_image' => ['nullable', 'string'],

            'password' => ['nullable', 'confirmed', 'min:6'],
        ]);

        $student = $user->student;

        /*
        |--------------------------------------------------------------------------
        | ตั้งชื่อรูปโปรไฟล์
        |--------------------------------------------------------------------------
        | ตัวอย่าง: STD-PK-70630162_20260709_214500.jpg
        */
        $role = 'STD';
        $schoolCode = $student?->school?->school_code ?? 'UNKNOWN';
        $studentCode = $student?->student_code ?? ('USER-' . $user->id);

        $schoolCode = preg_replace('/[^A-Za-z0-9_-]/', '', $schoolCode);
        $studentCode = preg_replace('/[^A-Za-z0-9_-]/', '', $studentCode);

        $timestamp = now()->format('Ymd_His');

        /*
        |--------------------------------------------------------------------------
        | อัปโหลดรูปโปรไฟล์
        |--------------------------------------------------------------------------
        | ตรวจไฟล์จริงก่อนเสมอ เพื่อไม่ให้ cropped_image เก่ามาขวาง
        */
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');

            if (! $file->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_image' => 'อัปโหลดรูปภาพไม่สำเร็จ กรุณาเลือกรูปใหม่อีกครั้ง',
                    ]);
            }

            $extension = strtolower($file->getClientOriginalExtension());

            if (! in_array($extension, ['jpg', 'jpeg', 'png', 'webp'], true)) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_image' => 'รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ WEBP',
                    ]);
            }

            $extension = $extension === 'jpeg' ? 'jpg' : $extension;

            $fileName = sprintf(
                '%s-%s-%s_%s.%s',
                $role,
                $schoolCode,
                $studentCode,
                $timestamp,
                $extension
            );

            if (
                $user->profile_image &&
                Storage::disk('public')->exists($user->profile_image)
            ) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $file->storeAs(
                'profiles',
                $fileName,
                'public'
            );
        } elseif ($request->filled('cropped_image')) {
            $image = $request->cropped_image;

            if (! preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $image, $matches)) {
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

            if (
                $user->profile_image &&
                Storage::disk('public')->exists($user->profile_image)
            ) {
                Storage::disk('public')->delete($user->profile_image);
            }

            Storage::disk('public')->put($imagePath, $decodedImage);

            $user->profile_image = $imagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | บันทึกตาราง users
        |--------------------------------------------------------------------------
        */
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->external_code = $validated['external_code'] ?? null;

        // ใช้ school_id จากตาราง students
            if ($student && $student->school_id) {
                $user->school_id = $student->school_id;
            }

        $user->load('school');

        
        $user->user_code = UserCodeService::generate($user);

        

        if (! $user->user_code) {
            return back()
                ->withErrors([
                    'external_code' => 'ไม่สามารถสร้าง User Code ได้'
                ])
                ->withInput();
        }
        
        $user->phone = $validated['phone'] ?? null;

        $user->address1 = $validated['address1'] ?? null;
        $user->address2 = $validated['address2'] ?? null;

        $user->province_id = $validated['province_id'] ?? null;
        $user->district_id = $validated['district_id'] ?? null;
        $user->subdistrict_id = $validated['subdistrict_id'] ?? null;


        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | อัปเดตรหัสนักเรียน (ถ้ามีการส่งค่าเข้ามา)
        |--------------------------------------------------------------------------
        */
        if ($student && ! empty($validated['student_code'])) {
            $student->update([
                'student_code' => $validated['external_code'],
            ]);
        }

        return back()->with('success', 'อัปเดตโปรไฟล์สำเร็จ 🎉');
    }
}