# Upgrading

## From 3.x to 4.x

- Run the following command to fetch the latest package version: `composer require f9webltd/laravel-meta:^4.0`
- The package now requires PHP `^8.2` and Laravel `^11.0` / `^12.0` or `^13.0`. Going forwards this package will actively track supported Laravel / PHP versions as per [Laravel's official support policy](https://laravel.com/docs/master/releases#support-policy)
- No further changes are required, the API remains the same

## From 2.x to 3.x

- This package now **automatically** encoded strings using `htmlentities`. This is a breaking change as already encoded strings may be passed to this package. See the [package readme](https://github.com/f9webltd/laravel-meta#quotes) for an updated usage example
- No further changes are required

## From 1.x to 2.x

- Run the following command to fetch the latets version of the package: `composer require f9webltd/laravel-meta:^2.0`
- The package now requires PHP `^8.0` and Laravel `^8.12` / `^9.0`, `^10.0` or `^11.0`
- The `meta()` helper function now **only** returns an instance of the `Meta` class, this is breaking change. Previously the helper acted as a short cut to fetch a specific tag. For example, `meta('title')` was previously the same as `meta()->get('title')`. This behaviour was removed for simplicity and to help with autocompletion. If using this function calls in the format `meta('title')` shoulod be adjusted to `meta()->get('title')`
- No further changes are required
