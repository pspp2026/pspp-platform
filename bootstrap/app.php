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
            // เฉพาะ admin
            'admin'    => \App\Http\Middleware\AdminOnly::class,

            // ต้องผ่านการอนุมัติ
            'approved' => \App\Http\Middleware\CheckApproved::class,
        ]);
    })

    // ❗ ห้ามลบเด็ดขาด
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();