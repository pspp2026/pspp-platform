<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Province;
use App\Models\Temple;
use App\Models\User;

use App\Services\UserCodeService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * =========================================================
     * Dashboard นักเรียน
     * =========================================================
     */
    public function index()
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | บทเรียนทั้งหมด
        |--------------------------------------------------------------------------
        */
        $lessons = Lesson::query()
            ->orderBy('id')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | บทเรียนที่นักเรียนเรียนจบแล้ว
        |--------------------------------------------------------------------------
        */
        $completedLessons = LessonProgress::query()
            ->where('user_id', $user->id)
            ->pluck('lesson_id')
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | จำนวนบทเรียนทั้งหมด
        |--------------------------------------------------------------------------
        */
        $totalLessons = $lessons->count();

        /*
        |--------------------------------------------------------------------------
        | จำนวนบทเรียนที่เรียนจบ
        |--------------------------------------------------------------------------
        */
        $completed = LessonProgress::query()
            ->where('user_id', $user->id)
            ->whereIn('lesson_id', $lessons->pluck('id'))
            ->count();

        /*
        |--------------------------------------------------------------------------
        | เปอร์เซ็นต์ความก้าวหน้า
        |--------------------------------------------------------------------------
        */
        $percent = $totalLessons > 0
            ? round(($completed / $totalLessons) * 100)
            : 0;

        /*
        |--------------------------------------------------------------------------
        | ส่งข้อมูลไป Dashboard
        |--------------------------------------------------------------------------
        */
        return view('student.dashboard', [
            'user' => $user,
            'lessons' => $lessons,
            'completedLessons' => $completedLessons,
            'percent' => $percent,
            'totalLessons' => $totalLessons,
            'completed' => $completed,
        ]);
    }


    /**
     * =========================================================
     * หน้า Profile นักเรียน
     * =========================================================
     */
    public function profile()
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | โหลดข้อมูลที่เกี่ยวข้องทั้งหมด
        |--------------------------------------------------------------------------
        */
        $user->load([
            'student.school',
            'student.temple',
            'student.enrollments',
        ]);

        $student = $user->student;

        /*
        |--------------------------------------------------------------------------
        | จังหวัด / อำเภอ / ตำบล สำหรับที่อยู่ปัจจุบัน
        |--------------------------------------------------------------------------
        */
        $provinces = Province::query()
            ->orderBy('name_th')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ข้อมูลวัด
        |--------------------------------------------------------------------------
        |
        | ใช้ข้อมูลจากตาราง temples
        | province / district / subdistrict
        |
        |--------------------------------------------------------------------------
        */
        $templeProvinces = Temple::query()
            ->whereNotNull('province')
            ->where('province', '!=', '')
            ->select('province')
            ->distinct()
            ->orderBy('province')
            ->pluck('province');

        /*
        |--------------------------------------------------------------------------
        | ค่าที่บันทึกไว้ของวัด
        |--------------------------------------------------------------------------
        */
        $selectedTempleProvince = old(
            'temple_province',
            $student?->temple?->province
        );

        $selectedTempleDistrict = old(
            'temple_district',
            $student?->temple?->district
        );

        $selectedTempleSubdistrict = old(
            'temple_subdistrict',
            $student?->temple?->subdistrict
        );

        $selectedTempleId = old(
            'temple_id',
            $student?->temple_id
        );

        /*
        |--------------------------------------------------------------------------
        | โหลดอำเภอของจังหวัดวัดที่เลือก
        |--------------------------------------------------------------------------
        */
        $templeDistricts = collect();

        if ($selectedTempleProvince) {
            $templeDistricts = Temple::query()
                ->where('province', $selectedTempleProvince)
                ->whereNotNull('district')
                ->where('district', '!=', '')
                ->select('district')
                ->distinct()
                ->orderBy('district')
                ->pluck('district');
        }

        /*
        |--------------------------------------------------------------------------
        | โหลดตำบลของอำเภอวัดที่เลือก
        |--------------------------------------------------------------------------
        */
        $templeSubdistricts = collect();

        if ($selectedTempleProvince && $selectedTempleDistrict) {
            $templeSubdistricts = Temple::query()
                ->where('province', $selectedTempleProvince)
                ->where('district', $selectedTempleDistrict)
                ->whereNotNull('subdistrict')
                ->where('subdistrict', '!=', '')
                ->select('subdistrict')
                ->distinct()
                ->orderBy('subdistrict')
                ->pluck('subdistrict');
        }

        /*
        |--------------------------------------------------------------------------
        | โหลดวัด
        |--------------------------------------------------------------------------
        */
        $temples = collect();

        if (
            $selectedTempleProvince &&
            $selectedTempleDistrict &&
            $selectedTempleSubdistrict
        ) {
            $temples = Temple::query()
                ->where('province', $selectedTempleProvince)
                ->where('district', $selectedTempleDistrict)
                ->where('subdistrict', $selectedTempleSubdistrict)
                ->orderBy('temple_name')
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Enrollment ล่าสุด
        |--------------------------------------------------------------------------
        */
        $enroll = $student
            ? $student->enrollments()
                ->latest('academic_year')
                ->latest('semester')
                ->first()
            : null;

        /*
        |--------------------------------------------------------------------------
        | ส่งข้อมูลไปหน้า Profile
        |--------------------------------------------------------------------------
        */
        return view('student.profile', [
            'user' => $user,
            'student' => $student,

            'provinces' => $provinces,

            'templeProvinces' => $templeProvinces,
            'templeDistricts' => $templeDistricts,
            'templeSubdistricts' => $templeSubdistricts,
            'temples' => $temples,

            'selectedTempleProvince' => $selectedTempleProvince,
            'selectedTempleDistrict' => $selectedTempleDistrict,
            'selectedTempleSubdistrict' => $selectedTempleSubdistrict,
            'selectedTempleId' => $selectedTempleId,

            'enroll' => $enroll,
        ]);
    }


    /**
     * =========================================================
     * บันทึกข้อมูล Profile นักเรียน
     * =========================================================
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            abort(403);
        }

        /*
        |--------------------------------------------------------------------------
        | โหลดข้อมูลนักเรียน
        |--------------------------------------------------------------------------
        */
        $user->load([
            'student.school',
            'student.temple',
        ]);

        $student = $user->student;

        /*
        |--------------------------------------------------------------------------
        | Validation
        |--------------------------------------------------------------------------
        */
        $validated = $request->validate([

            // users
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'external_code' => [
                'nullable',
                'string',
                'max:50',
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'address1' => [
                'nullable',
                'string',
            ],

            'address2' => [
                'nullable',
                'string',
            ],

            'province_id' => [
                'nullable',
            ],

            'district_id' => [
                'nullable',
            ],

            'subdistrict_id' => [
                'nullable',
            ],

            /*
            |----------------------------------------------------------------------
            | ZIP Code
            |----------------------------------------------------------------------
            |
            | รับค่าที่หน้า Profile เติมให้อัตโนมัติหลังเลือกตำบล
            |
            */
            'zipcode' => [
                'nullable',
                'string',
                'max:10',
            ],

            // student
            'prefix' => [
                'nullable',
                'string',
                'max:50',
            ],

            'first_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:100',
            ],

            // temple
            'temple_province' => [
                'nullable',
                'string',
                'max:100',
            ],

            'temple_district' => [
                'nullable',
                'string',
                'max:100',
            ],

            'temple_subdistrict' => [
                'nullable',
                'string',
                'max:100',
            ],

            'temple_id' => [
                'nullable',
                'integer',
            ],

            // image
            'profile_image' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'cropped_image' => [
                'nullable',
                'string',
            ],

            // password
            'password' => [
                'nullable',
                'confirmed',
                'min:6',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | ตรวจว่ามีนักเรียนหรือไม่
        |--------------------------------------------------------------------------
        */
        if (! $student) {
            return back()
                ->withInput()
                ->withErrors([
                    'student' => 'ไม่พบข้อมูลนักเรียนที่เชื่อมโยงกับบัญชีนี้',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | ตั้งชื่อรูปโปรไฟล์
        |--------------------------------------------------------------------------
        |
        | school_id ยึดจาก students เป็นอันดับหนึ่ง
        |
        |--------------------------------------------------------------------------
        */
        $role = 'STD';

        $schoolCode = $student?->school?->school_code ?? 'UNKNOWN';

        /*
        | external_code สามารถเป็น NULL ได้
        | จึงใช้ student_code เดิมเป็น fallback
        | และใช้ USER-ID เฉพาะกรณีไม่มีรหัสใด ๆ สำหรับชื่อไฟล์
        */
        $studentCode = $validated['external_code']
            ?? $student?->student_code
            ?? ('USER-' . $user->id);

        $schoolCode = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '',
            $schoolCode
        );

        $studentCode = preg_replace(
            '/[^A-Za-z0-9_-]/',
            '',
            $studentCode
        );

        $timestamp = now()->format('Ymd_His');

        /*
        |--------------------------------------------------------------------------
        | อัปโหลดรูปแบบไฟล์จริง
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('profile_image')) {

            $file = $request->file('profile_image');

            if (! $file->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_image' =>
                            'อัปโหลดรูปภาพไม่สำเร็จ กรุณาเลือกรูปใหม่อีกครั้ง',
                    ]);
            }

            $extension = strtolower(
                $file->getClientOriginalExtension()
            );

            if (! in_array(
                $extension,
                ['jpg', 'jpeg', 'png', 'webp'],
                true
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_image' =>
                            'รองรับเฉพาะไฟล์ JPG, JPEG, PNG และ WEBP',
                    ]);
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

            /*
            | ลบรูปเดิม
            */
            if (
                $user->profile_image &&
                Storage::disk('public')->exists(
                    $user->profile_image
                )
            ) {
                Storage::disk('public')->delete(
                    $user->profile_image
                );
            }

            /*
            | บันทึกรูปใหม่
            */
            $user->profile_image = $file->storeAs(
                'profiles',
                $fileName,
                'public'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | รองรับ cropped image จาก Cropper
        |--------------------------------------------------------------------------
        */
        elseif ($request->filled('cropped_image')) {

            $image = $request->cropped_image;

            if (! preg_match(
                '/^data:image\/(jpeg|jpg|png|webp);base64,/',
                $image,
                $matches
            )) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'cropped_image' =>
                            'รูปภาพที่ตัดไม่อยู่ในรูปแบบที่รองรับ',
                    ]);
            }

            $extension = strtolower($matches[1]);

            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            $imageData = preg_replace(
                '/^data:image\/(jpeg|jpg|png|webp);base64,/',
                '',
                $image
            );

            $imageData = str_replace(
                ' ',
                '+',
                $imageData
            );

            $decodedImage = base64_decode(
                $imageData,
                true
            );

            if ($decodedImage === false) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'cropped_image' =>
                            'ไม่สามารถอ่านข้อมูลรูปภาพได้',
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

            /*
            | ลบรูปเดิม
            */
            if (
                $user->profile_image &&
                Storage::disk('public')->exists(
                    $user->profile_image
                )
            ) {
                Storage::disk('public')->delete(
                    $user->profile_image
                );
            }

            /*
            | บันทึกรูป
            */
            Storage::disk('public')->put(
                $imagePath,
                $decodedImage
            );

            $user->profile_image = $imagePath;
        }

        /*
        |--------------------------------------------------------------------------
        | USERS
        |--------------------------------------------------------------------------
        */
        $user->name = $validated['name'];

        $user->email = $validated['email'];

        $user->external_code =
            $validated['external_code'] ?? null;

        $user->phone =
            $validated['phone'] ?? null;

        $user->address1 =
            $validated['address1'] ?? null;

        $user->address2 =
            $validated['address2'] ?? null;

        $user->province_id =
            $validated['province_id'] ?? null;

        $user->district_id =
            $validated['district_id'] ?? null;

        $user->subdistrict_id =
            $validated['subdistrict_id'] ?? null;

       

        /*
        |--------------------------------------------------------------------------
        | SCHOOL
        |--------------------------------------------------------------------------
        |
        | สำคัญ:
        | students.school_id เป็นแหล่งข้อมูลหลัก
        | ผู้ใช้ไม่สามารถเปลี่ยน school_id ผ่าน Profile ได้
        |
        |--------------------------------------------------------------------------
        */
        if ($student->school_id) {
            $user->school_id = $student->school_id;
        }

        /*
        |--------------------------------------------------------------------------
        | สร้าง User Code ใหม่
        |--------------------------------------------------------------------------
        |
        | school_id ถูกกำหนดจาก students ก่อนเรียก Service
        |
        |--------------------------------------------------------------------------
        */
        $user->load('school');

        $generatedUserCode =
            UserCodeService::generate($user);

        if (! $generatedUserCode) {
            return back()
                ->withInput()
                ->withErrors([
                    'external_code' =>
                        'ไม่สามารถสร้าง User Code ได้',
                ]);
        }

        $user->user_code = $generatedUserCode;

        /*
        |--------------------------------------------------------------------------
        | เปลี่ยน Password
        |--------------------------------------------------------------------------
        */
        if (! empty($validated['password'])) {
            $user->password = Hash::make(
                $validated['password']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | STUDENT
        |--------------------------------------------------------------------------
        */
        $student->prefix =
            $validated['prefix'] ?? $student->prefix;

        $student->first_name =
            $validated['first_name'] ?? $student->first_name;

        $student->last_name =
            $validated['last_name'] ?? $student->last_name;

        /*
        |--------------------------------------------------------------------------
        | รหัสนักเรียน
        |--------------------------------------------------------------------------
        |
        | external_code เป็นแหล่งข้อมูลหลัก
        | และสามารถเป็น NULL ได้
        |
        |--------------------------------------------------------------------------
        */
        if (array_key_exists('external_code', $validated)) {
            $student->student_code =
                $validated['external_code'];
        }

        /*
        |--------------------------------------------------------------------------
        | TEMPLE
        |--------------------------------------------------------------------------
        */
        if (array_key_exists('temple_id', $validated)) {
            $student->temple_id =
                $validated['temple_id'] ?: null;
        }

        /*
        |--------------------------------------------------------------------------
        | บันทึก USERS
        |--------------------------------------------------------------------------
        */
        $user->save();

        /*
        |--------------------------------------------------------------------------
        | บันทึก STUDENT
        |--------------------------------------------------------------------------
        */
        $student->save();

        /*
        |--------------------------------------------------------------------------
        | สำเร็จ
        |--------------------------------------------------------------------------
        */
        return back()->with(
            'success',
            'อัปเดตโปรไฟล์สำเร็จ 🎉'
        );
    }
}