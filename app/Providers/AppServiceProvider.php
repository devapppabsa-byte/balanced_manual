<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Contracts\Foundation\ExceptionRenderer;
use Illuminate\Foundation\Exceptions\Whoops\WhoopsExceptionRenderer;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->hasDebugModeEnabled()) {
            $this->app->singleton(ExceptionRenderer::class, WhoopsExceptionRenderer::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Carbon::setLocale('es');

        // con esta linea ayuda a que mis estillos se vean en ngrok y en local
        if (str_contains(request()->getHost(), 'ngrok')) {
            URL::forceScheme('https');
        }

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

    }
}
