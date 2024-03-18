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
        $config = config('f9web-laravel-meta');

        if (!($title = Arr::get($tags ?? [], 'title'))) {
            return new HtmlString(
                sprintf('<title>%s</title>', $config['fallback-meta-title'])
            );
        }

        if (
            isset($config['meta-title-replacements']['enabled']) &&
            $config['meta-title-replacements']['enabled'] === true
        ) {
            $title = str_replace(
                $config['meta-title-replacements']['search'] ?? [],
                $config['meta-title-replacements']['replace'] ?? [],
                $title
            );
        }

        if ($append = $config['meta-title-append']) {
            $title .= ' - ' . $append;
        }

        $title = Str::limit($title, $config['title-limit'] ?? 999, null);

        return new HtmlString(sprintf('<title>%s</title>', $title));
    }
}
