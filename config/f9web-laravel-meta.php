<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default tags
    |--------------------------------------------------------------------------
    |
    | The following tags are set by default on every request. Default tags can
    | can be set using key / value or string format. The latter will default
    | to setting a raw meta tag.
    |
    */

    'defaults' => [
        // '<meta name="format-detection" content="telephone=no">',
        // 'robots' => 'all',
        // 'referrer' => 'no-referrer-when-downgrade',
    ],

    'title-guessor' => [

        /*
        |--------------------------------------------------------------------------
        | Meta title guessing
        |--------------------------------------------------------------------------
        |
        | Enable or disable meta title guessing. This is useful for large systems
        | with lots of controllers with consistent restful urls, where setting
        | a meta title within every controller method is undesirable.
        |
        */

        'enabled' => true,

        /*
        |--------------------------------------------------------------------------
        | Guessing method
        |--------------------------------------------------------------------------
        |
        | Can be "route" or "uri". "route" will guess based on current route name.
        | "uri" will guess based on the current uri. For example, if the uri is
        | "users/99/edit" the title would be "Users - 23 - Edit". If the route
        | name "users.profile", the title would be "Users - Profile".
        |
        */

        'method' => 'uri',
    ],

    /*
    |--------------------------------------------------------------------------
    | Url segments to always remove from the canonical url
    |--------------------------------------------------------------------------
    |
    | An array of uri components to automatically remove when rendering
    | the canonical tag
    |
    */

    'removable-uri-segments' => [
        '/public',
        '/index.php',
    ],

    /*
    |--------------------------------------------------------------------------
    | Limit title meta tag length
    |--------------------------------------------------------------------------
    |
    | Google typically displays the first 50–60 characters of a title tag. To
    | avoid limiting the title set this value to null.
    |
    */

    'meta-title-append' => 'Meta Title Append',

    /*
    |--------------------------------------------------------------------------
    | Fallback meta title
    |--------------------------------------------------------------------------
    |
    | Value used when a meta title has not been set and automatic
    | guessing is disabled
    |
    */

    'fallback-meta-title' => env('APP_NAME'),

    /*
    |--------------------------------------------------------------------------
    | Favicon path
    |--------------------------------------------------------------------------
    |
    | Optional, used when the "favIcon()" helper is called
    |
    */

    'favicon-path' => asset('favicon.ico'),

    /*
    |--------------------------------------------------------------------------
    | Limit title meta tag length
    |--------------------------------------------------------------------------
    |
    | Google typically displays the first 50–60 characters of a title tag. To
    | avoid limiting the title set this value to null.
    |
    */

    'title-limit' => 60,

    /*
    |--------------------------------------------------------------------------
    | Limit description meta tag length
    |--------------------------------------------------------------------------
    |
    | Meta descriptions can be any length, but Google generally truncates
    | snippets to ~155–160 characters. Keep in mind that the "optimal"
    | length will vary depending on the situation, and your primary
    | goal should be to provide value and drive clicks. Set to null
    | to remove any limiting
    |
    */

    'description-limit' => null,
];
