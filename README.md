# Render meta tags within your Laravel application

Easily render meta taggs within your Laravel application

## Installation

You can install the package via composer:

``` bash
composer require f9web/laravel-meta
```

The package will automatically register itself if using Laravel >= 5.5.

You can optionally publish the config file with:

```bash
php artisan vendor:publish --provider="F9Web\Meta\MetaServiceProvider" --tag="config"
```

## Documentation

Basic usage instructions to follow.

Use the `meta()` helper of the Meta facade

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
