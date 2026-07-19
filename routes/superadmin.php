<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\Survey\SurveyController;
use App\Http\Controllers\SuperAdmin\OnlineUserController;
use App\Http\Controllers\SuperAdmin\UserLoginLogController;

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
        'track.online',
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

        Route::get('/online-users', [OnlineUserController::class, 'index'])
            ->name('online-users');

        Route::get('/user-login-logs', [UserLoginLogController::class, 'index'])
        ->name('user-login-logs.index');

    });