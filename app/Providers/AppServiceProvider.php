<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use App\Models\Setting;
use Illuminate\Support\Facades\URL; 

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Vite::prefetch(concurrency: 3);

        Paginator::useTailwind();

        try {
            $settings = Setting::pluck('details', 'term')->toArray();

            if (isset($settings['historyImg'])) {
                $settings['historyImg'] = json_decode($settings['historyImg'], true);
            }
            if (isset($settings['bgImg'])) {
                $settings['bgImg'] = json_decode($settings['bgImg'], true);
            }

            View::share('settings', $settings);
        } catch (\Throwable $e) {
        }
    }
}