<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use function config;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

use function sprintf;

class Description implements Tag
{
    /**
     * {@inheritdoc}
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        return new HtmlString(
            sprintf(
                '<meta name="%s" content="%s">',
                $key,
                Str::limit($value, config('f9web-laravel-meta.description-limit') ?? 9999, null)
            )
        );
    }
}
