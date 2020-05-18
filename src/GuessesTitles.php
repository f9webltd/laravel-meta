<?php

namespace F9Web\Meta;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

use function config;
use function optional;

trait GuessesTitles
{
    public static function setDefaultTitle(): void
    {
        self::set('title', self::guessTitle() ?? '');
    }

    private static function guessTitle(): ?string
    {
        $title = (new TitleGuessor())
            ->withUri(Request::path())
            ->withRoute(optional(Route::current())->getName())
            ->render();

        if ($title === null && ($fallback = config('f9web-laravel-meta.fallback-meta-title'))) {
            return $fallback;
        }

        return $title;
    }
}
