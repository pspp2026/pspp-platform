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

        // ปิดชั่วคราว
        // $middleware->append(
        //     \App\Http\Middleware\CheckPsppEvaluation::class
        // );

        /*
        |--------------------------------------------------------------------------
        | Route Middleware Alias
        |--------------------------------------------------------------------------
        */

        $middleware->alias([

            'admin' => \App\Http\Middleware\AdminOnly::class,
            'approved' => \App\Http\Middleware\CheckApproved::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'school.admin' => \App\Http\Middleware\EnsureSchoolAdmin::class,

        ]);

    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();