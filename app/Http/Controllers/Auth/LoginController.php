<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
//โลโก้ จากไฟล์ /    public\images\logoBitpps.png

// แสดงฟอร์ม login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // ประมวลผล login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
            ]);
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // ❗ ยังไม่ผ่านอนุมัติ
        if ($user->status !== 'approved') {
            return redirect()->route('pending');
        }

        // ✅ ผ่านอนุมัติแล้ว → redirect ตาม role
        return match ($user->role) {
            'admin'    => redirect()->route('dashboard'),
            'teacher'  => redirect('/teacher/dashboard'),
            'student'  => redirect('/student/home'),
            'staff'    => redirect('/staff/dashboard'),
            'director' => redirect('/director/dashboard'),
            default    => abort(403),
        };
    }

    // logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}