<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

interface Tag
{
    /**
     * @param  string  $key
     * @param  null  $value
     * @param  \Illuminate\Support\Collection|null  $tags
     * @return HtmlString
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString;
}
