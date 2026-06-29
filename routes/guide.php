<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GuideController;

Route::middleware(['auth'])->group(function () {

    Route::get(
        '/guides/student-import',
        [GuideController::class, 'studentImport']
    )->name('guides.student-import');

});