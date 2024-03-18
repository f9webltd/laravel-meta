<?php

use F9Web\Meta\Meta;

if (!function_exists('meta')) {
    function meta(?string $key = null): Meta
    {
        return resolve(Meta::class);
    }
}
