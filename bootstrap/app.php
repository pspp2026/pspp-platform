<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            // 👇 admin เท่านั้น
            'admin'    => \App\Http\Middleware\AdminOnly::class,

            // 👇 ต้อง approved ก่อน
            'approved' => \App\Http\Middleware\CheckApproved::class,

            // 👇 ✅ เพิ่มตัวนี้ (แก้ error ของคุณ)
            'role'     => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })

    // ❗ ห้ามลบเด็ดขาด
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();