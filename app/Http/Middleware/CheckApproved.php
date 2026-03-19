<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApproved
{
    public function handle(Request $request, Closure $next)
    {
        // ยังไม่ login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // login แล้ว แต่ยังไม่อนุมัติ
        if (Auth::user()->status !== 'approved') {
            return redirect()->route('pending');
        }

        return $next($request);
    }
}