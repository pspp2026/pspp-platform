<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AcademicTerm;
use App\Models\Classroom;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Period;
use App\Models\Province;
use App\Models\Schedule;
use App\Models\School;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Temple;
use App\Models\User;
use App\Services\UserCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * หน้า Dashboard นักเรียน
     */
    public function index()
    {
        $userId = Auth::id();
        $lessons = Lesson::all();
        $totalLessons = $lessons->count();

        $completedLessons = LessonProgress::where('user_id', $userId)
            ->pluck('lesson_id')
            ->toArray();

        $completedCount = count($completedLessons);

        $percent = $totalLessons > 0
            ? round(($completedCount / $totalLessons) * 100)
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

        $user->load([
            'student.enrollments',
            'student.school',
            'student.temple',
        ]);

        /*
        |--------------------------------------------------------------------------
        | ข้อมูลที่อยู่ของผู้ใช้
        |--------------------------------------------------------------------------
        */
        $provinces = Province::query()
            ->orderBy('name_th')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ข้อมูลวัด
        |--------------------------------------------------------------------------
        */
        $templeProvinces = Temple::query()
            ->whereNotNull('province')
            ->where('province', '!=', '')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        $selectedTemple = $user->student?->temple;
        $selectedTempleProvince = $selectedTemple?->province;
        $selectedTempleDistrict = $selectedTemple?->district;
        $selectedTempleSubdistrict = $selectedTemple?->subdistrict;
        $selectedTempleId = $selectedTemple?->id;

        // โหลดอำเภอ
        $templeDistricts = $selectedTempleProvince
            ? Temple::query()
                ->where('province', $selectedTempleProvince)
                ->whereNotNull('district')
                ->where('district', '!=', '')
                ->distinct()
                ->orderBy('district')
                ->pluck('district')
            : collect();

        // โหลดตำบล
        $templeSubdistricts = ($selectedTempleProvince && $selectedTempleDistrict)
            ? Temple::query()
                ->where('province', $selectedTempleProvince)
                ->where('district', $selectedTempleDistrict)
                ->whereNotNull('subdistrict')
                ->where('subdistrict', '!=', '')
                ->distinct()
                ->orderBy('subdistrict')
                ->pluck('subdistrict')
            : collect();

        // โหลดวัด
        $temples = ($selectedTempleProvince && $selectedTempleDistrict && $selectedTempleSubdistrict)
            ? Temple::query()
                ->where('province', $selectedTempleProvince)
                ->where('district', $selectedTempleDistrict)
                ->where('subdistrict', $selectedTempleSubdistrict)
                ->orderBy('temple_name')
                ->get(['id', 'temple_name', 'province', 'district', 'subdistrict'])
            : collect();

        return view('student.profile', compact(
            'user',
            'provinces',
            'templeProvinces',
            'templeDistricts',
            'templeSubdistricts',
            'temples',
            'selectedTempleProvince',
            'selectedTempleDistrict',
            'selectedTempleSubdistrict',
            'selectedTempleId'
        ));
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

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'external_code' => ['required', 'string', 'max:50'],
            'prefix' => ['nullable', 'string', 'max:50'],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address1' => ['nullable', 'string'],
            'address2' => ['nullable', 'string'],
            'province_id' => ['nullable'],
            'district_id' => ['nullable'],
            'subdistrict_id' => ['nullable'],
            'temple_id' => ['nullable', 'exists:temples,id'],
            'profile_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'cropped_image' => ['nullable', 'string'],
            'password' => ['nullable', 'confirmed', 'min:6'],
        ]);

        $oldProfileImage = $user->profile_image;
        $newProfilePath = null;

        // ทำการบันทึกข้อมูลหลักผ่าน Transaction เพื่อความปลอดภัยของข้อมูล
        DB::beginTransaction();
        try {
            $student = $user->student;

            // 1. Sync ข้อมูลนักเรียนก่อน (ถ้ามี)
            if ($student) {
                $student->update([
                    'student_code' => $validated['external_code'] ?? $student->student_code,
                    'prefix'       => $validated['prefix'] ?? $student->prefix,
                    'first_name'   => $validated['first_name'] ?? $student->first_name,
                    'last_name'    => $validated['last_name'] ?? $student->last_name,
                    'temple_id'    => $validated['temple_id'] ?? null,
                ]);

                $student->refresh();
            }

            // 2. เติมข้อมูลลง User
            $user->fill([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'external_code' => $validated['external_code'] ?? null,
                'school_id' => $student?->school_id ?? $user->school_id,
                'phone' => $validated['phone'] ?? null,
                'address1' => $validated['address1'] ?? null,
                'address2' => $validated['address2'] ?? null,
                'province_id' => $validated['province_id'] ?? null,
                'district_id' => $validated['district_id'] ?? null,
                'subdistrict_id' => $validated['subdistrict_id'] ?? null,
            ]);

            if (! empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            // บันทึก User รอบแรกเพื่อให้ DB นิ่งก่อน
            $user->save();

            // 3. สร้าง User Code (โหลดความสัมพันธ์ school ใหม่ให้ชัวร์)
            $user->unsetRelation('school');
            $user->load('school');
            $userCode = UserCodeService::generate($user);

            if (! $userCode) {
                DB::rollBack();
                return back()
                    ->withErrors(['external_code' => 'ไม่สามารถสร้าง User Code ได้ (กรุณาตรวจสอบการตั้งค่าโรงเรียนของนักเรียน)'])
                    ->withInput();
            }

            // อัปเดต user_code และเซฟอีกครั้ง
            $user->user_code = $userCode;
            $user->save();

            /*
            |--------------------------------------------------------------------------
            | การประมวลผลไฟล์รูปภาพ
            |--------------------------------------------------------------------------
            */
            $role = 'STD';
            $schoolCode = preg_replace('/[^A-Za-z0-9_-]/', '', $student?->school?->school_code ?? 'UNKNOWN');
            $studentCode = preg_replace('/[^A-Za-z0-9_-]/', '', $student?->student_code ?? $user->external_code ?? ('USER-' . $user->id));
            $timestamp = now()->format('Ymd_His');

            // กรณีอัปโหลดรูปไฟล์ปกติ
            if ($request->hasFile('profile_image')) {
                $file = $request->file('profile_image');

                if (! $file->isValid()) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['profile_image' => 'อัปโหลดรูปภาพไม่สำเร็จ กรุณาเลือกรูปใหม่อีกครั้ง']);
                }

                $extension = strtolower($file->getClientOriginalExtension());
                $extension = ($extension === 'jpeg') ? 'jpg' : $extension;
                $fileName = sprintf('%s-%s-%s_%s.%s', $role, $schoolCode, $studentCode, $timestamp, $extension);

                $newProfilePath = $file->storeAs('profiles', $fileName, 'public');

            // กรณีอัปโหลดรูป Base64 จากการ Crop บนเบราว์เซอร์
            } elseif ($request->filled('cropped_image')) {
                $image = $request->cropped_image;

                if (! preg_match('/^data:image\/(jpeg|jpg|png|webp);base64,/', $image, $matches)) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['cropped_image' => 'รูปภาพที่ตัดไม่อยู่ในรูปแบบที่รองรับ']);
                }

                $extension = strtolower($matches[1]);
                $extension = ($extension === 'jpeg') ? 'jpg' : $extension;

                $imageData = base64_decode(preg_replace('/^data:image\/(jpeg|jpg|png|webp);base64,/', '', $image), true);

                if ($imageData === false) {
                    DB::rollBack();
                    return back()->withInput()->withErrors(['cropped_image' => 'ไม่สามารถอ่านข้อมูลรูปภาพได้']);
                }

                $fileName = sprintf('%s-%s-%s_%s.%s', $role, $schoolCode, $studentCode, $timestamp, $extension);
                $newProfilePath = 'profiles/' . $fileName;

                Storage::disk('public')->put($newProfilePath, $imageData);
            }

            // ถ้ามีรูปใหม่ เซฟ path ใหม่และลบรูปเก่าออกจาก disk
            if ($newProfilePath) {
                $user->profile_image = $newProfilePath;
                $user->save();

                if ($oldProfileImage && Storage::disk('public')->exists($oldProfileImage)) {
                    Storage::disk('public')->delete($oldProfileImage);
                }
            }

            DB::commit();

            return back()->with('success', 'อัปเดตโปรไฟล์สำเร็จ 🎉');

        } catch (\Exception $e) {
            DB::rollBack();

            if ($newProfilePath && Storage::disk('public')->exists($newProfilePath)) {
                Storage::disk('public')->delete($newProfilePath);
            }

            return back()->withInput()->withErrors(['error' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage()]);
        }
    }
}