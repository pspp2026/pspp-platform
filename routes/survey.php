<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Survey\SurveyController;

Route::middleware(['auth'])
    ->prefix('survey')
    ->name('survey.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Survey Dashboard
        |--------------------------------------------------------------------------
        */

        Route::get('/', [SurveyController::class, 'index'])
            ->name('index');

        /*
        |--------------------------------------------------------------------------
        | PSPP Evaluation
        |--------------------------------------------------------------------------
        */

        // แสดงแบบประเมิน
        Route::get('/pspp/evaluation', [SurveyController::class, 'psppEvaluation'])
            ->name('pspp.evaluation');

        // บันทึกคำตอบ
        Route::post('/pspp/evaluation', [SurveyController::class, 'submitPsppEvaluation'])
            ->name('pspp.submit');

        /*
        |--------------------------------------------------------------------------
        | Survey CRUD
        |--------------------------------------------------------------------------
        */

        Route::get('/create', [SurveyController::class, 'create'])
            ->name('create');

        Route::post('/', [SurveyController::class, 'store'])
            ->name('store');

        Route::get('/{survey}/edit', [SurveyController::class, 'edit'])
            ->name('edit');

        Route::put('/{survey}', [SurveyController::class, 'update'])
            ->name('update');

        Route::delete('/{survey}', [SurveyController::class, 'destroy'])
            ->name('destroy');

        Route::get('/{survey}', [SurveyController::class, 'show'])
            ->name('show');

    });