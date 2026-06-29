<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdmin\DashboardController;
// use App\Http\Controllers\SuperAdmin\SchoolController;
// use App\Http\Controllers\SuperAdmin\UserController;

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware([
        'auth',
        'approved',
        'role:superadmin',
    ])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Dashboard
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        /*
        |--------------------------------------------------------------------------
        | Schools
        |--------------------------------------------------------------------------
        */
        // Route::resource('schools', SchoolController::class);

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        // Route::resource('users', UserController::class);

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */
        // Route::resource('reports', ReportController::class);

        /*
        |--------------------------------------------------------------------------
        | Settings
        |--------------------------------------------------------------------------
        */
        // Route::resource('settings', SettingController::class);

    });