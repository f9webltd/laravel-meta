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
     * @param  string  $key
     * @param  null  $value
     * @param  \Illuminate\Support\Collection|null  $tags
     * @return HtmlString
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        $string = null;

        if ($defaultTitle = config('f9web-laravel-meta.fallback-meta-title')) {
            $string = sprintf('<title>%s</title>', $defaultTitle);
        }

        if ($title = Arr::get($tags, 'title')) {
            if ($append = config('f9web-laravel-meta.meta-title-append')) {
                $title .= ' - '.$append;
            }

            if ($limit = config('f9web-laravel-meta.title-limit')) {
                $title = Str::limit($title, $limit, null);
            }

            $string = sprintf('<title>%s</title>', $title);
        }

        if (null === $string) {
            $string = '<title></title>';
        }

        return new HtmlString($string);
    }
}
