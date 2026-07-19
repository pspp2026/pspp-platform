<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\UserOnline;
use Illuminate\View\View;

class OnlineUserController extends Controller
{
    /**
     * แสดงรายการผู้ใช้ออนไลน์
     */
    public function index(): View
    {
        $onlineUsers = UserOnline::with('user.school')
            ->whereHas('user', function ($query) {
                $query->where('role', '!=', 'superadmin');
            })
            ->orderByDesc('last_activity')
            ->paginate(25);

        return view('superadmin.online-users', [
            'onlineUsers' => $onlineUsers,
            'onlineUsersCount' => $onlineUsers->total(),
        ]);
    }
}