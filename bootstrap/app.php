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

        /*
        |--------------------------------------------------------------------------
        | Global Middleware
        |--------------------------------------------------------------------------
        */

        $middleware->append(
            \App\Http\Middleware\CheckPsppEvaluation::class
        );

        /*
        |--------------------------------------------------------------------------
        | Route Middleware Alias
        |--------------------------------------------------------------------------
        */

        $middleware->alias([

            // Admin เท่านั้น
            'admin' => \App\Http\Middleware\AdminOnly::class,

            // ต้อง approved ก่อน
            'approved' => \App\Http\Middleware\CheckApproved::class,

            // ตรวจสอบ Role
            'role' => \App\Http\Middleware\RoleMiddleware::class,

            // Admin ของแต่ละโรงเรียน
            'school.admin' => \App\Http\Middleware\EnsureSchoolAdmin::class,

        ]);

    })

    /*
    |--------------------------------------------------------------------------
    | Exception Handler
    |--------------------------------------------------------------------------
    */

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();