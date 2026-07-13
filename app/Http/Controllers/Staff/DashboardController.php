<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\User;
use App\Models\School;
use App\Models\Temple;

class DashboardController extends Controller
{
    public function index()
    {
        $staff = Staff::with(['school', 'user', 'temple'])
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('staff.dashboard', compact('staff'));
    }
}