<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use function config;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;

use function sprintf;
use function str_replace;

class Canonical implements Tag
{
    /**
     * {@inheritdoc}
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        $url = $value ?? URL::current();

        if ($replacements = config('f9web-laravel-meta.removable-uri-segments')) {
            $url = str_replace($replacements, '', $value);
        }

        return new HtmlString(
            sprintf('<link rel="%s" href="%s" />', $key, $url)
        );
    }
}
