<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * แสดงผู้ใช้ทั้งหมด
     */
    public function index(): View
    {
        $users = User::with('school')
            ->latest()
            ->paginate(20);

        return view('superadmin.users.index', compact('users'));
    }

    /**
     * แสดงผู้ใช้ที่รออนุมัติ
     */
    public function pending(): View
    {
        $users = User::with('school')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view('superadmin.users.pending', compact('users'));
    }

    /**
     * แสดงฟอร์มแก้ไขผู้ใช้
     */
    public function edit(User $user): View
    {
        return view('superadmin.users.edit', compact('user'));
    }

    /**
     * บันทึกการแก้ไขข้อมูลผู้ใช้
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user->update($validated);

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'แก้ไขข้อมูลผู้ใช้เรียบร้อยแล้ว');
    }

    /**
     * ลบผู้ใช้
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }
}