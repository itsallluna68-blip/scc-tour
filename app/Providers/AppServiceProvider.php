<?php

namespace App\Providers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Vite;
use Illuminate\Pagination\Paginator;


use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */




    public function boot(): void
    {
        // Force HTTPS if the application is not running locally
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
        Paginator::useTailwind();
    }
}

