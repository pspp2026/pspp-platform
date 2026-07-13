<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Services\UserCodeService;

use App\Models\User;
use App\Models\Province;
use App\Models\School;

class ProfileController extends Controller
{
public function profile()
    {
        return view('staff.profile', [
            'schools' => School::all(),
            'provinces' => Province::orderBy('name_th', 'asc')->get(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        // ✅ validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'external_code' => 'nullable|string|max:50',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048|dimensions:min_width=100,min_height=100',
        ]);
      
        
        // =========================
        // 🖼️ upload profile image
        // =========================
        if ($request->hasFile('profile_image')) {

            $file = $request->file('profile_image');

            // 🔥 ใช้ user_code เป็นชื่อไฟล์
            $userCode = $user->user_code ?? 'USER_UNKNOWN';

            $time = now()->format('Ymd_His');
            $ext = $file->getClientOriginalExtension();

            $filename = "{$userCode}_{$time}.{$ext}";

            // 🔥 folder (เดี๋ยวเราจะปรับเป็น school_code ใน step ต่อไป)
            $folder = "profiles";

            // 🔥 ลบรูปเก่า
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // 🔥 เก็บไฟล์
            $path = $file->storeAs($folder, $filename, 'public');

            // 🔥 save path
            $user->profile_image = $path;
        }

        // =========================
        // 🖼️ save cropped image
        // =========================
        if ($request->cropped_image) {

            $image = $request->cropped_image;

            // 🔥 ตัด header base64 ออก
            $image = str_replace('data:image/jpeg;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            // 🔥 แปลงเป็น binary
            $imageData = base64_decode($image);

            // 🔥 ตั้งชื่อไฟล์ (ใช้ user_code)
            $userCode = $user->user_code ?? 'USER';
            $filename = $userCode . '_' . now()->format('Ymd_His') . '.jpg';

            // 🔥 folder (เดี๋ยวเราจะ upgrade เป็น school_code)
            $path = "profiles/" . $filename;

            // 🔥 ลบรูปเก่า
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            // 🔥 save
            Storage::disk('public')->put($path, $imageData);

            $user->profile_image = $path;
        }

        // =========================
        // 🔥 assign (ไม่ใช้ update)
        // =========================
        $user->name = $request->name;
        $user->email = $request->email;

        $user->id_card = $request->id_card;
        $user->name_th = $request->name_th;
        $user->name_en = $request->name_en;

        $user->address1 = $request->address1;
        $user->address2 = $request->address2;

        $user->province_id = $request->province_id;
        $user->district_id = $request->district_id;
        $user->subdistrict_id = $request->subdistrict_id;

        $user->phone = $request->phone;

        $user->school_id = $request->school_id;
        $user->external_code = $request->external_code;

       // =========================
      // Generate user_code
    // =========================
    $user->load('school');

    $user->user_code = UserCodeService::generate($user);

    if (!$user->user_code) {
        return back()
            ->withErrors([
                'external_code' => 'ไม่สามารถสร้าง User Code ได้ กรุณาตรวจสอบข้อมูลโรงเรียน บทบาท และรหัสประจำตัว'
            ])
            ->withInput();
    }
       
        // =========================
        // 🔐 password (ถ้ามี)
        // =========================
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // =========================
        // ✅ save ครั้งเดียว
        // =========================
        $user->save();
        $staff = $user->staff;

        if ($staff) {

            $staff->school_id = $request->school_id;

            // ถ้ายังใช้ staff_code
            $staff->staff_code = $user->external_code;

            $staff->save();
        }
        return back()->with('success', 'บันทึกข้อมูลเรียบร้อย');
    }

}