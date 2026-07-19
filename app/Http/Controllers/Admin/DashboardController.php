<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id;

        return view('admin.dashboard', [

            'totalUsers' => User::where('school_id', $schoolId)->count(),

            'pendingUsers' => User::where('school_id', $schoolId)
                ->where('status', 'pending')
                ->count(),

            'approvedUsers' => User::where('school_id', $schoolId)
                ->where('status', 'approved')
                ->count(),

            'rejectedUsers' => User::where('school_id', $schoolId)
                ->where('status', 'rejected')
                ->count(),

            // แสดงเฉพาะโรงเรียนของ Admin
            'schools' => User::join('schools', 'users.school_id', '=', 'schools.id')
                ->select('schools.school_name', DB::raw('COUNT(*) as total'))
                ->where('users.school_id', $schoolId)
                ->groupBy('schools.school_name')
                ->get(),
        ]);
    }
    
}