<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class Name implements Tag
{
    /**
     * @param  string  $key
     * @param  null  $value
     * @param  \Illuminate\Support\Collection|null  $tags
     * @return \Illuminate\Support\HtmlString
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        return new HtmlString("<meta name=\"{$key}\" content=\"{$value}\">");
    }
}
