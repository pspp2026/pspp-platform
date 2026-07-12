<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\Survey\SurveyController;

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/

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
        | Users
        |--------------------------------------------------------------------------
        */

        Route::get('/users/pending', [UserController::class, 'pending'])
            ->name('users.pending');

        Route::resource('users', UserController::class);

        /*
        |--------------------------------------------------------------------------
        | Surveys
        |--------------------------------------------------------------------------
        */

        Route::resource('surveys', SurveyController::class);

        /*
        |--------------------------------------------------------------------------
        | Schools
        |--------------------------------------------------------------------------
        */

        // Route::resource('schools', SchoolController::class);

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