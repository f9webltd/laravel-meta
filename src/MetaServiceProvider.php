<?php

namespace F9Web\Meta;

use Illuminate\Support\ServiceProvider;

class MetaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/f9web-laravel-meta.php' => config_path('f9web-laravel-meta.php'),
        ], 'config');

        $this->mergeConfigFrom(__DIR__ . '/../config/f9web-laravel-meta.php', 'f9web-laravel-meta');
    }

    public function register()
    {
        $this->app->singleton(Meta::class);

        $this->app->alias(Meta::class, 'meta');
    }
}
