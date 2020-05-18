<?php

namespace F9Web\Meta;

use function config;

trait MetaHelpers
{
    /**
     * @return \F9Web\Meta\Meta
     */
    public static function noIndex(): Meta
    {
        return self::set('robots', 'noindex nofollow');
    }

    /**
     * @param  string  $src
     * @return \F9Web\Meta\Meta
     */
    public static function favIcon(?string $src = null): Meta
    {
        self::set('shortcut icon', ($icon = $src ?? config('f9web-laravel-meta.favicon-path')));

        return self::setRawTag('<link rel="icon" type="image/x-icon" href="'.$icon.'">');
    }
}
