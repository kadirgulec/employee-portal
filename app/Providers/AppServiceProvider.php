<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Spatie\Translatable\Facades\Translatable;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Translatable::fallback(
            fallbackAny: true,
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

    }
}
