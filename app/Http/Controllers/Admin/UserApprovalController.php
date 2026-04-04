<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserApprovalController extends Controller
{
    // แสดง user ที่รออนุมัติ
    public function index()
    {
        $users = User::where('status', 'pending')->get();

        return view('admin.users.pending', compact('users'));
    }

    // อนุมัติทีละคน
    public function approve(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string',
        ]);

        $user->update([
            'status'      => 'approved',
            'role'        => $request->role,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'อนุมัติผู้ใช้เรียบร้อยแล้ว');
    }

    // 🔥 อนุมัติหลายคน (เพิ่มใหม่)
    public function approveBulk(Request $request)
    {
        $ids = $request->user_ids;

        if (!$ids) {
            return back()->with('error', 'กรุณาเลือกผู้ใช้');
        }

        User::whereIn('id', $ids)->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'อนุมัติสำเร็จ ' . count($ids) . ' คน');
    }
}