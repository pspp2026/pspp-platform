<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // 👈 เพิ่มบรรทัดนี้

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // 👇 เพิ่มตรงนี้
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}