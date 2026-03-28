<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckApproved
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // ถ้ามี user และยังไม่ถูกอนุมัติ
        if ($user && $user->status !== 'approved') {

            // อนุญาตให้เข้าหน้า pending ได้ (กัน loop)
            if ($request->routeIs('pending')) {
                return $next($request);
            }

            // เด้งไปหน้า pending
            return redirect()->route('pending');
        }

        return $next($request);
    }
}