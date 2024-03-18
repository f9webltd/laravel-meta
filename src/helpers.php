<?php

use F9Web\Meta\Meta;

if (!function_exists('meta')) {
    function meta(): Meta
    {
        return resolve(Meta::class);
    }
}
