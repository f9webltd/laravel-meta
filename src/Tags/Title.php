<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use function config;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use function sprintf;

class Title implements Tag
{
    /**
     * {@inheritdoc}
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        if (! ($title = Arr::get($tags ?? [], 'title'))) {
            return new HtmlString(
                sprintf('<title>%s</title>', config('f9web-laravel-meta.fallback-meta-title'))
            );
        }

        if ($append = config('f9web-laravel-meta.meta-title-append')) {
            $title .= ' - ' . $append;
        }

        $title = Str::limit($title, config('f9web-laravel-meta.title-limit') ?? 999, null);

        return new HtmlString(sprintf('<title>%s</title>', $title));
    }
}
