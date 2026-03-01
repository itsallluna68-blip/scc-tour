<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
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
        Vite::prefetch(concurrency: 3);

        // Share settings (term => details) with all views
        try {
            $settings = Setting::pluck('details', 'term')->toArray();

            // decode json fields if present
            if (isset($settings['historyImg'])) {
                $settings['historyImg'] = json_decode($settings['historyImg'], true);
            }
            if (isset($settings['bgImg'])) {
                $settings['bgImg'] = json_decode($settings['bgImg'], true);
            }

            View::share('settings', $settings);
        } catch (\Throwable $e) {
            // fail silently in case DB not available during certain artisan commands
        }
    }
}
