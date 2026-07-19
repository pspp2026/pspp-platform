<?php

namespace App\Http\Middleware;

use App\Services\OnlineUserService;
use Closure;
use Illuminate\Http\Request;

class TrackOnlineUsers
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        app(OnlineUserService::class)->update();

        return $response;
    }
}