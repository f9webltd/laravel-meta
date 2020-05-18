<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use function str_replace;

class Property implements Tag
{
    /**
     * {@inheritdoc}
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        $key = str_replace('property:', null, $key);

        return new HtmlString("<meta property=\"{$key}\" content=\"{$value}\">");
    }
}
