<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\UserOnline;
use App\Models\UserLoginLog;

class LoginController extends Controller
{
    /**
     * แสดงหน้าเข้าสู่ระบบ
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * ประมวลผลการเข้าสู่ระบบ
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
                ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();


        /*
        |--------------------------------------------------------------------------
        | ตรวจสถานะการอนุมัติ
        |--------------------------------------------------------------------------
        */
        if ($user->status !== 'approved') {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('pending')
                ->with('error', 'บัญชีของคุณยังไม่ได้รับการอนุมัติ');
        }

        

        /*
        |--------------------------------------------------------------------------
        | บันทึกประวัติการ Login
        |--------------------------------------------------------------------------
        */
        UserLoginLog::create([
            'user_id'    => $user->id,
            'school_id'  => $user->school_id,
            'role'       => $user->role,
            'login_at'   => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'session_id' => $request->session()->getId(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | ส่งผู้ใช้ไปยัง Dashboard ตามสิทธิ์
        |--------------------------------------------------------------------------
        */
        return match ($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            'teacher' => redirect()->route('teacher.dashboard'),
            'student' => redirect()->route('student.dashboard'),
            'staff' => redirect()->route('staff.dashboard'),
            'director' => redirect()->route('director.dashboard'),

            default => abort(403, 'ไม่พบสิทธิ์ผู้ใช้งาน'),
        };
    }

    /**
     * ออกจากระบบ
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();

        if ($userId) {

            // ลบออกจากผู้ใช้ออนไลน์
            UserOnline::where('user_id', $userId)->delete();

            // บันทึกเวลา Logout
            UserLoginLog::where('user_id', $userId)
                ->whereNull('logout_at')
                ->latest('login_at')
                ->first()?->update([
                    'logout_at' => now(),
                ]);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}