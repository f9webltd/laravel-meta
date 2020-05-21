<?php

declare(strict_types=1);

namespace F9Web\Meta\Tags;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

use function sprintf;
use function str_replace;

class Property implements Tag
{
    /**
     * {@inheritdoc}
     */
    public function render(string $key, $value = null, Collection $tags = null): HtmlString
    {
        return new HtmlString(
            sprintf(
                '<meta property="%s" content="%s">',
                str_replace('property:', null, $key),
                $value
            )
        );
    }
}
