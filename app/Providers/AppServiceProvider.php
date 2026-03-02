<?php

namespace App\Providers;

use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        View::composer('*', function ($view) {
            try {
                $settings = Setting::getAllSettings();
                $view->with('siteSettings', $settings);
            } catch (Exception $e) {
                $view->with('siteSettings', []);
            }
        });
    }
}
