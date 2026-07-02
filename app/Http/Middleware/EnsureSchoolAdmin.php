<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSchoolAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin' || !$user->school_id) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงส่วนจัดการโรงเรียน');
        }

        return $next($request);
    }
}