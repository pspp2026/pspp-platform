<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth()->user();

        // ❌ ยังไม่ได้ login
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        // 🔥 normalize ค่า (กันพลาด)
        $userRole = strtolower(trim($user->role));

        $allowedRoles = array_map(function ($role) {
            return strtolower(trim($role));
        }, $roles);

        // ❌ role ไม่ตรง
        if (!in_array($userRole, $allowedRoles)) {

            // 🔥 debug (เปิดใช้ชั่วคราวได้)
            // dd($userRole, $allowedRoles);

            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}