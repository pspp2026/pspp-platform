<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\UserLoginLog;
use App\Models\School;
use Illuminate\Http\Request;

class UserLoginLogController extends Controller
{
    public function index(Request $request)
    {
        $query = UserLoginLog::with(['user','school'])
            ->latest('login_at');

        if ($request->filled('school_id')) {
            $query->where('school_id',$request->school_id);
        }

        if ($request->filled('role')) {
            $query->where('role',$request->role);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->whereHas('user',function($q) use ($keyword){

                $q->where('name','like',"%{$keyword}%")
                  ->orWhere('email','like',"%{$keyword}%");

            });
        }

        $logs = $query->paginate(20);

        return view(
            'superadmin.login-logs.index',
            [
                'logs'=>$logs,
                'schools'=>School::orderBy('school_name')->get(),
            ]
        );
    }
}