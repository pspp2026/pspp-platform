<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\Province;
use App\Models\User;
use App\Models\Temple;
use App\Services\UserCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * หน้าโปรไฟล์นักเรียน
     */
    public function profile()
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
            'student.enrollments',
            'student.school',
            'student.temple',
        ]);

        $student = $user->student;

        /*
        |--------------------------------------------------------------------------
        | จังหวัดสำหรับที่อยู่
        |--------------------------------------------------------------------------
        */
        $provinces = Province::query()
            ->orderBy('name_th')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | ข้อมูลเดิมของวัด
        |--------------------------------------------------------------------------
        */
        $selectedTempleProvince = $student?->temple?->province;
        $selectedTempleDistrict = $student?->temple?->district;
        $selectedTempleSubdistrict = $student?->temple?->subdistrict;
        $selectedTempleId = $student?->temple_id;

        /*
        |--------------------------------------------------------------------------
        | จังหวัดที่มีวัด
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
        | อำเภอของวัด
        |--------------------------------------------------------------------------
        | โหลดเฉพาะกรณีที่นักเรียนมีจังหวัดเดิม
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
        | ตำบลของวัด
        |--------------------------------------------------------------------------
        */
        $templeSubdistricts = collect();

        if (
            $selectedTempleProvince &&
            $selectedTempleDistrict
        ) {
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
        | วัด
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
                ->get([
                    'id',
                    'temple_name',
                    'province',
                    'district',
                    'subdistrict',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | การลงทะเบียนล่าสุด
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
        | ส่งข้อมูลไป View
        |--------------------------------------------------------------------------
        */
        return view('student.profile', compact(
            'user',
            'student',
            'provinces',
            'enroll',

            // Temple
            'templeProvinces',
            'templeDistricts',
            'templeSubdistricts',
            'temples',

            // ค่าเดิม
            'selectedTempleProvince',
            'selectedTempleDistrict',
            'selectedTempleSubdistrict',
            'selectedTempleId'
        ));
    }


    /**
     * AJAX: ดึงอำเภอของวัดตามจังหวัด
     */
    public function templeDistricts(string $province)
    {
        $districts = Temple::query()
            ->where('province', $province)
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->select('district')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        return response()->json($districts);
    }


    /**
     * AJAX: ดึงตำบลของวัดตามจังหวัด + อำเภอ
     */
    public function templeSubdistricts(
        string $province,
        string $district
    ) {
        $subdistricts = Temple::query()
            ->where('province', $province)
            ->where('district', $district)
            ->whereNotNull('subdistrict')
            ->where('subdistrict', '!=', '')
            ->select('subdistrict')
            ->distinct()
            ->orderBy('subdistrict')
            ->pluck('subdistrict');

        return response()->json($subdistricts);
    }


    /**
     * AJAX: ดึงวัดตามจังหวัด + อำเภอ + ตำบล
     */
    public function temples(
        string $province,
        string $district,
        string $subdistrict
    ) {
        $temples = Temple::query()
            ->where('province', $province)
            ->where('district', $district)
            ->where('subdistrict', $subdistrict)
            ->orderBy('temple_name')
            ->get([
                'id',
                'temple_name',
            ]);

        return response()->json($temples);
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

        $user->load([
            'student.school',
        ]);

        $validated = $request->validate([
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


            'temple_id' => [
                'nullable',
                'exists:temples,id',
            ],

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

            'password' => [
                'nullable',
                'confirmed',
                'min:6',
            ],
        ]);

        $student = $user->student;

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        $user->external_code =
            $validated['external_code'] ?? null;

        /*
        |--------------------------------------------------------------------------
        | school_id
        |--------------------------------------------------------------------------
        */
        if ($student && $student->school_id) {
            $user->school_id = $student->school_id;
        }

        /*
        |--------------------------------------------------------------------------
        | User Code
        |--------------------------------------------------------------------------
        */
        $user->load('school');

        $user->user_code =
            UserCodeService::generate($user);

        if (! $user->user_code) {
            return back()
                ->withErrors([
                    'external_code' =>
                        'ไม่สามารถสร้าง User Code ได้',
                ])
                ->withInput();
        }

        /*
        |--------------------------------------------------------------------------
        | Contact
        |--------------------------------------------------------------------------
        */
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
        | Password
        |--------------------------------------------------------------------------
        */
        if (! empty($validated['password'])) {
            $user->password =
                Hash::make($validated['password']);
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Student
        |--------------------------------------------------------------------------
        */
        if ($student) {

            $student->update([
                'student_code' =>
                    $user->external_code,

                'prefix' =>
                    $validated['prefix'] ?? $student->prefix,

                'first_name' =>
                    $validated['first_name'] ?? $student->first_name,

                'last_name' =>
                    $validated['last_name'] ?? $student->last_name,

                'temple_id' =>
                    $validated['temple_id'] ?? null,
            ]);

            $student->refresh();
        }

        /*
        |--------------------------------------------------------------------------
        | ชื่อไฟล์รูป
        |--------------------------------------------------------------------------
        */
        $role = 'STD';

        $schoolCode =
            $student?->school?->school_code
            ?? 'UNKNOWN';

        $studentCode =
            $student?->student_code
            ?? $user->external_code
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

        $timestamp =
            now()->format('Ymd_His');

        /*
        |--------------------------------------------------------------------------
        | Upload Profile Image
        |--------------------------------------------------------------------------
        */
        if ($request->hasFile('profile_image')) {

            $file =
                $request->file('profile_image');

            if (! $file->isValid()) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_image' =>
                            'อัปโหลดรูปภาพไม่สำเร็จ กรุณาเลือกรูปใหม่อีกครั้ง',
                    ]);
            }

            $extension =
                strtolower(
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
            |----------------------------------------------------------------------
            | ลบรูปเก่า
            |----------------------------------------------------------------------
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
            |----------------------------------------------------------------------
            | บันทึกรูปใหม่
            |----------------------------------------------------------------------
            */
            $user->profile_image =
                $file->storeAs(
                    'profiles',
                    $fileName,
                    'public'
                );

            $user->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Cropped Image
        |--------------------------------------------------------------------------
        */
        elseif ($request->filled('cropped_image')) {

            $image =
                $request->cropped_image;

            if (
                ! preg_match(
                    '/^data:image\/(jpeg|jpg|png|webp);base64,/',
                    $image,
                    $matches
                )
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'cropped_image' =>
                            'รูปภาพที่ตัดไม่อยู่ในรูปแบบที่รองรับ',
                    ]);
            }

            $extension =
                strtolower($matches[1]);

            if ($extension === 'jpeg') {
                $extension = 'jpg';
            }

            $imageData =
                preg_replace(
                    '/^data:image\/(jpeg|jpg|png|webp);base64,/',
                    '',
                    $image
                );

            $imageData =
                str_replace(
                    ' ',
                    '+',
                    $imageData
                );

            $decodedImage =
                base64_decode(
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

            $imagePath =
                'profiles/' . $fileName;

            /*
            |----------------------------------------------------------------------
            | ลบรูปเก่า
            |----------------------------------------------------------------------
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
            |----------------------------------------------------------------------
            | บันทึกรูป
            |----------------------------------------------------------------------
            */
            Storage::disk('public')->put(
                $imagePath,
                $decodedImage
            );

            $user->profile_image =
                $imagePath;

            $user->save();
        }

        return back()->with(
            'success',
            'อัปเดตโปรไฟล์สำเร็จ 🎉'
        );
    }
}