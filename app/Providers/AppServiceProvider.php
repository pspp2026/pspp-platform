<?php

namespace App\Providers;

use App\Models\Enrollment;
use App\Policies\EnrollmentPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // บังคับใช้ HTTPS บน Production
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        // Register Policies
        Gate::policy(
            Enrollment::class,
            EnrollmentPolicy::class
        );
    }
}