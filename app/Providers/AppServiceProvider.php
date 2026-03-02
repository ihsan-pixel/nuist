<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use App\Models\DevelopmentHistory;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\Notifications\ResetPassword;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Schema::defaultStringLength(191);

        // Share current app settings with all views from database AppSetting
        View::composer('*', function ($view) {
            $settings = \App\Models\AppSetting::getSettings();
            $view->with('app_name', $settings->app_name)
                 ->with('app_version', $settings->app_version);
        });

        // Use mobile reset-password route in password reset emails so users open mobile-optimized page
        ResetPassword::createUrlUsing(function ($notifiable, $token) {
            return url(route('mobile.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));
        });
    }

    /**
     * Get current app version
     */
    private function getCurrentAppVersion()
    {
        // Try to get from cache first
        $cachedVersion = Cache::get('app_version');
        if ($cachedVersion) {
            return $cachedVersion;
        }

        // Get from latest development history
        $latestHistory = DevelopmentHistory::whereNotNull('version')
                                          ->orderBy('development_date', 'desc')
                                          ->first();

        if ($latestHistory && $latestHistory->version) {
            $version = $latestHistory->version;
            Cache::put('app_version', $version, now()->addHours(1));
            return $version;
        }

        // Fallback to config version
        $version = config('app.version', '1.0.0');
        Cache::put('app_version', $version, now()->addHours(1));
        return $version;
    }
}
