<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('director.dashboard');
    }
}