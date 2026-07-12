<?php

namespace App\Providers;

use App\Models\Enrollment;
use App\Models\PsppEvaluation;
use App\Policies\EnrollmentPolicy;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Force HTTPS
        |--------------------------------------------------------------------------
        */

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        /*
        |--------------------------------------------------------------------------
        | Register Policies
        |--------------------------------------------------------------------------
        */

        Gate::policy(
            Enrollment::class,
            EnrollmentPolicy::class
        );

        /*
        |--------------------------------------------------------------------------
        | Survey Popup
        |--------------------------------------------------------------------------
        */

        View::composer('*', function ($view) {

            $showSurveyPopup = false;

            if (Auth::check()) {

                // ❌ ไม่แสดง Popup ในหน้าแบบประเมิน
                if (
                    request()->routeIs('survey.pspp.evaluation') ||
                    request()->routeIs('survey.pspp.submit')
                ) {

                    $view->with('showSurveyPopup', false);

                    return;
                }

                $showSurveyPopup = !PsppEvaluation::where(
                    'user_id',
                    Auth::id()
                )->exists();
            }

            $view->with('showSurveyPopup', $showSurveyPopup);

        });
    }
}