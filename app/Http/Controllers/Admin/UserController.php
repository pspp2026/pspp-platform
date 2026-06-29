<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * แสดงผู้ใช้ทั้งหมด
     */
    public function index()
    {
        $users = User::with('school')
            ->latest()
            ->paginate(20);

        return view(
            'admin.users.index',
            compact('users')
        );
    }

    /**
     * แสดงผู้ใช้ที่รออนุมัติ
     */
    public function pending()
    {
        $users = User::where('status', 'pending')
            ->orderBy('created_at')
            ->get();

        return view(
            'admin.users.pending',
            compact('users')
        );
    }

    /**
     * แสดงฟอร์มแก้ไขผู้ใช้
     */
    public function edit(User $user)
    {
        return view(
            'admin.users.edit',
            compact('user')
        );
    }

    /**
     * บันทึกการแก้ไขข้อมูลผู้ใช้
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|max:255',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'แก้ไขข้อมูลผู้ใช้เรียบร้อยแล้ว');
    }

    /**
     * ลบผู้ใช้
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'ลบผู้ใช้เรียบร้อยแล้ว');
    }
}