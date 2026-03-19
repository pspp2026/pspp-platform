<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // 1) Validate (กันข้อมูลไม่พึงประสงค์)
        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email|unique:users,email',
            'password'    => 'required|string|min:8|confirmed',
            'school_code' => 'required|string|max:20',
        ]);

        // 2) สร้างผู้ใช้ (สำคัญ)
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'school_code' => strtoupper($request->school_code),
            'role'        => 'teacher',   // ค่าเริ่มต้น
            'status'      => 'pending',   // ❗ ต้องรออนุมัติ
        ]);

        // 3) Login ได้ แต่ยังใช้งานไม่ได้
        Auth::login($user);

        // 4) พาไปหน้า “รอการอนุมัติ”
        return redirect()->route('pending');
    }
}