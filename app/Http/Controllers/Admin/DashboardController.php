<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'pendingUsers' => User::where('status', 'pending')->count(),
            'approvedUsers' => User::where('status', 'approved')->count(),
            'rejectedUsers' => User::where('status', 'rejected')->count(),

            // แยกตามโรงเรียน (ใช้ school_id + join)
            'schools' => User::join('schools', 'users.school_id', '=', 'schools.id')
                ->select('schools.school_name', DB::raw('COUNT(*) as total'))
                ->groupBy('schools.school_name')
                ->orderByDesc('total')
                ->get(),
        ]);
    }
}