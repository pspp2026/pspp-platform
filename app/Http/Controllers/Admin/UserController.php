<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // 🔥 อนุมัติหลายคน
    public function approveBulk(Request $request)
    {
        $ids = $request->user_ids;

        if (!$ids) {
            return back()->with('error', 'กรุณาเลือกผู้ใช้');
        }

        User::whereIn('id', $ids)->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return back()->with('success', 'อนุมัติสำเร็จ ' . count($ids) . ' คน');
    }
}