[![Latest Stable Version](https://poser.pugx.org/f9web/laravel-meta/v)](https://packagist.org/packages/f9web/laravel-meta)
[![Build Status](https://travis-ci.org/f9webltd/laravel-meta.svg)](https://travis-ci.org/f9webltd/laravel-meta)
[![StyleCI Status](https://github.styleci.io/repos/264978205/shield)](https://github.styleci.io/repos/264978205)
[![License](https://poser.pugx.org/f9web/laravel-meta/license)](https://packagist.org/packages/f9web/laravel-meta)

# Render meta tags within your Laravel application

Easily render meta tags within your Laravel application, using a fluent API

## Requirements

PHP >= 7.2, Laravel >= 5.5.

## Installation

``` bash
composer require f9web/laravel-meta
```

The package will automatically register itself if using Laravel >= 5.5.

Optionally publish the configuration file by running:

```bash
php artisan vendor:publish --provider="F9Web\Meta\MetaServiceProvider" --tag="config"
```

The configuration file is published to `config/f9web-laravel-meta`.

## Documentation

This package aims to make adding common meta tags to your Laravel application a breeze. 

 ### Usage
 
Within a controller:
 
 ```php
meta()
    ->set('title', 'Buy widgets today')
    ->set('canonical', '/users/name')
    ->set('description', 'My meta description')
    ->set('theme-color', '#fafafa')
    ->noIndex();
```

To output metadata add the following within a Blade layout file:

 ```php
{!! meta()->toHtml() !!}
```

 ```html
<title>Buy widgets today - Meta Title Append</title>
<link rel="canonical" href="https://site.co.uk/users/name" />
<meta name="description" content="My meta description">
<meta name="theme-color" content="#fafafa">
<meta name="robots" content="noindex nofollow">
```

Optionally, the `Meta` facade can be used as an alternative to `meta()` helper, generating the same output:

 ```php
Meta::set('title', 'Buy widgets today')
    ->set('canonical', '/users/name')
    ->set('description', 'My meta description')
    ->set('theme-color', '#fafafa')
    ->noIndex();
```

### Blade Directives

Blade directives are available, as an alternative to using PHP function within templates.

To render all metadata:

```html
@meta
```

Render a specific metatag by name.:

```html
@meta('title')
```

### Additional tag types

The package supports multiple tag types.

#### Property type tags

To create property type tags, append `property:` before the tag name.

```php
meta()->set('property:fb:app_id', '1234567890');
```

```html
<meta property="fb:app_id" content="1234567890">
```

#### Twitter card tags

To create twitter card tags, append `twitter:` before the tag name.

```php 
meta()->set('twitter:site', '@twitter_user');
```

 ```html
<meta name="twitter:site" content="@twitter_user">
```

#### Open Graph tags

To create Open Graph (or Facebook) tags, append `og:` before the tag name:

 ```php
meta()
    ->set('og:title', 'My new site')
    ->set('og:url', 'http://site.co.uk/posts/hello.html');
```

 ```html
<meta property="og:title" content="My new site">
<meta property="og:url" content="http://site.co.uk/posts/hello.html">
```

#### Other tag types

To create other tag types, use the `raw()` method:

 ```php
meta()
    ->setRawTag('<link rel="fluid-icon" href="https://gist.github.com/fluidicon.png" title="GitHub">')
    ->setRawTag('<link rel="search" type="application/opensearchdescription+xml" href="/opensearch-gist.xml" title="Gist">');
```

 ```html
<link rel="fluid-icon" href="https://gist.github.com/fluidicon.png" title="GitHub">
<link rel="search" type="application/opensearchdescription+xml" href="/opensearch-gist.xml" title="Gist">
```

### Default tags

It may be desirable to render static meta tags application wide. Optionally define common tags within `f9web-laravel-meta.defaults`.

For example, defining the below defaults

```php
'defaults' => [
    'robots' => 'all',
    'referrer' => 'no-referrer-when-downgrade',
    '<meta name="format-detection" content="telephone=no">',
],
```

will render the following on every page:

```html
<meta name="robots" content="all">
<meta name="referrer" content="no-referrer-when-downgrade">
<meta name="format-detection" content="telephone=no">
```

### Helper methods

#### `get()`

Fetch a specific tag value by name.

```php
meta()->set('title', 'meta title');

meta()->get('title'); // meta title
```

`null` is returned for none existent tags.

#### `render()`

Render all defined tags. `render()` is called when rendering tags within Blade files.

The below calls are identical.

 ```php
{!! meta()->toHtml() !!}
{!! meta()->render() !!}
```

Passing a tag title to `render()` will render that tag.

```php
meta()->set('title', 'meta title');

meta()->render('title'); // <title>meta title</title>
```

#### `fromArray()`

Generate multiple tags from an array of tags.

 ```php
meta()
    ->fromArray([
        'viewport' => 'width=device-width, initial-scale=1.0',
        'author' => 'John Joe',
        'og:title' => 'When Great Minds Dont Think Alike',
        'twitter:title' => 'Using Laravel 7',
    ]);
```

```html
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="John Joe">
<meta property="og:title" content="When Great Minds Dont Think Alike">
<meta name="twitter:title" content="Using Laravel 7">
<title>Users - Edit - Meta Title Append</title>
```

#### `setRawTags()`

Generate multiple raw tags from an array.

 ```php
meta()
    ->setRawTags([
        '<link rel="alternate" type="application/rss+xml" title="New Releases - Packagist" href="https://packagist.org/feeds/releases.rss" />',
        '<link rel="search" type="application/opensearchdescription+xml" href="/search.osd?v=1588083369" title="Packagist" />',
        '<meta charset="UTF-8" />'
    ]);
```

```html
<link rel="alternate" type="application/rss+xml" title="New Releases - Packagist" href="https://packagist.org/feeds/releases.rss" />
<link rel="search" type="application/opensearchdescription+xml" href="/search.osd?v=1588083369" title="Packagist" />
<meta charset="UTF-8" />
```

#### `tags()`

Fetch all tags as an array.

 ```php
meta()
    ->set('title', 'meta title')
    ->set('og:title', 'og title');

$tags = meta()->tags();

/*
[
    "title" => "meta title"
    "og:title" => "og title"
];
*/
```

#### `purge()`

Remove all previously set tags.

#### `forget()`

Remove a previously set tag by title.

 ```php
meta()
    ->set('title', 'meta title')
    ->set('og:title', 'og title');

meta()->forget('title');

$tags = meta()->tags();

// ["og:title" => "og title"];
```

#### `noIndex()`

Generate the necessary tags to exclude the url from search engines.

 ```php
meta()->noIndex();
```

 ```html
<meta name="robots" content="noindex nofollow">
```

#### `favIcon()`

Generate the necessary tags for a basic favicon. The favicon path can be specified within the `f9web-laravel-meta.favicon-path` configuration value.

 ```php
meta()->favIcon();
```

 ```html
<meta name="shortcut icon" content="https://site.co.uk/favicon.ico">
<link rel="icon" type="image/x-icon" href="https://site.co.uk/favicon.ico">
```

### Dynamic Calls

For improved readability, it is possible to make dynamic method calls. The below codes blocks would render identical HTML:

```php
meta()
    ->title('meta title')
    ->description('meta description')
    ->canonical('/users/me');
```

```php
meta()
    ->set('title', 'meta title')
    ->set('description', 'meta description')
    ->set('canonical', '/users/me');
```

### Special tags

#### Meta title

The package ensures a meta tag is always present. Omitting a title will force the package to guess one.

Two methods available, `route` (based on the current named route) and `uri` (the default, based on the raw uri segments). 

The guessing method is configurable via the `f9web-laravel-meta.title-guessor.method` config value. 

Using the `uri` method, the meta title for `uri` `/orders/create` would be "Orders - Create".

Similarly, the `uri` `/orders/9999/edit` would generate the title "Orders - 9999 - Edit".

Using the `route` method, slightly different titles are generated, minus parameters. 

The route name `users.create` would generate 'Users - Create' and `users.index` would generate "Users".

Title guessing can be disabled by adjusting the `f9web-laravel-meta.title-guessor.enabled` configuration value.

This automatic resolution is extremely useful in large applications, where it would be otherwise cumbersome to set metadata for every controller method.
 
##### Appending text to the meta title

Typically, common data such as the company name is appended to meta titles.
 
 The `f9web-laravel-meta.meta-title-append` configuration value can be set to append the given string automatically to every meta title. 
 
 To disable this behaviour set `f9web-laravel-meta.meta-title-append` to `null`.
  
##### Limiting the meta title length

For SEO reasons, the meta title should typically remain less than ~60 characters. This package, by default, limits the title to 60 characters.

To change this behaviour update the configuration value of `f9web-laravel-meta.title-limit`. Set to `null` to stop limiting.

#### Meta description
 
For SEO reasons, the meta description should typically remain less than ~160 characters. This package, by default, does not limit the length.
 
To change the limit adjust the configuration value `f9web-laravel-meta.description-limit`. Set to `null` to stop limiting.

#### Canonical

It is important to set a sensible canonical. Optionally, the package can automatically replace user defined strings when generating a canonical.

Due to incorrect setup some Laravel installations allow `public` and/or `index.php` within the url.

For instance, `/users/create` and `/public/index.php/users/create` would both be visitable, crawlable and ultimately indexable urls.

By editing the array of removable url strings within `f9web-laravel-meta.removable-uri-segments`, this behaviour can be controlled.

The package will strip `public` and `index.php` from canonical urls automatically, as a default.

## Testing

``` bash
composer test
```

## Security

If you discover any security related issues, please email rob@f9web.co.uk instead of using the issue tracker.

## Credits

- [Rob Allport](https://github.com/ultrono)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
