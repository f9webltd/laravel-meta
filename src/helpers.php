<?php

use F9Web\Meta\Meta;

if (! function_exists('meta')) {
    /**
     * @param  string|null  $key
     * @return array|\F9Web\Meta\Meta|string|null
     */
    function meta(?string $key = null)
    {
        $instance = resolve(Meta::class);

        if ($key === null) {
            return $instance;
        }

        return $instance->get($key);
    }
}
