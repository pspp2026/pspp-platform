<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next)
    {
        // ยังไม่ login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // ไม่ใช่ admin
        if (Auth::user()->role !== 'admin') {
            abort(403); // Forbidden
        }

        return $next($request);
    }
}