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

    // อนุมัติ user + เลือก role
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

        return redirect()->back()->with('success', 'อนุมัติผู้ใช้เรียบร้อยแล้ว');
    }
}