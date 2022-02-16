<?php

namespace F9Web\Meta;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

use function config;
use function sprintf;

class MetaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes(
            [
                __DIR__ . '/../config/f9web-laravel-meta.php' => config_path('f9web-laravel-meta.php'),
            ],
            'config'
        );

        $this->mergeConfigFrom(__DIR__ . '/../config/f9web-laravel-meta.php', 'f9web-laravel-meta');

        $this->registerBladeDirectives();

        $this->registerMacros();
    }

    public function register()
    {
        $this->app->singleton(Meta::class);

        $this->app->alias(Meta::class, 'meta');
    }

    private function registerMacros(): void
    {
        Meta::macro(
            'noIndex',
            function () {
                Meta:forget('robots');
                return Meta::set('robots', 'noindex nofollow');
            }
        );

        Meta::macro(
            'favIcon',
            function (?string $src = null) {
                Meta::set('shortcut icon', ($icon = $src ?? config('f9web-laravel-meta.favicon-path')));

                return Meta::setRawTag('<link rel="icon" type="image/x-icon" href="' . $icon . '">');
            }
        );
    }

    private function registerBladeDirectives(): void
    {
        Blade::directive(
            'meta',
            function ($expression) {
                return sprintf('<?php echo meta()->render(%s); ?>', $expression);
            }
        );
    }
}
