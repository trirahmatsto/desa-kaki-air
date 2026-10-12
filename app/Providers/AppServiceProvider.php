<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

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
        if ($this->app->runningInConsole()) {
            return;
        }

        $scheme = request()->isSecure()
            || request()->header('X-Forwarded-Proto') === 'https'
            ? 'https'
            : 'http';

        // ponytail: reads X-Forwarded-Proto directly (ngrok / reverse-proxy TLS). Spoofable by direct clients; restrict via trusted-proxy config if exposed publicly.
        URL::forceScheme($scheme);
    }
}
