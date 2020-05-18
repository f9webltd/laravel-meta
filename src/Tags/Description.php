<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use function config;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;

class Description implements Tag
{
    /**
     * {@inheritdoc}
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        if ($limit = config('f9web-laravel-meta.description-limit')) {
            $value = Str::limit($value, $limit, null);
        }

        return new HtmlString("<meta name=\"{$key}\" content=\"{$value}\">");
    }
}
