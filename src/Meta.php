<?php

declare(strict_types=1);

namespace F9Web\Meta;

use Closure;
use F9Web\Meta\Tags\Name;
use F9Web\Meta\Tags\Property;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

use function array_map;
use function class_exists;
use function config;
use function implode;
use function is_int;
use function ucwords;

class Meta implements Htmlable
{
    use GuessesTitles;
    use Macroable {
        __call as macroCall;
    }

    /** @var \Illuminate\Support\Collection|null */
    protected static $tags = null;

    /** @var array|\Illuminate\Support\Collection|null */
    protected static $rawTags = [];

    /** @var null|Meta */
    private static $_instance = null;

    /**
     * @param  array  $tags
     * @return \F9Web\Meta\Meta
     */
    public static function setRawTags(array $tags = []): self
    {
        array_map(
            function ($tag) {
                self::setRawTag($tag);
            },
            $tags
        );

        return self::instance();
    }

    /**
     * @param  string  $value
     * @return \F9Web\Meta\Meta
     */
    public static function setRawTag(string $value): self
    {
        if (self::$rawTags === []) {
            self::$rawTags = new Collection();
        }

        self::$rawTags->push($value);

        return self::instance();
    }

    /**
     * @return \F9Web\Meta\Meta|null
     */
    public static function instance(): ?self
    {
        if (self::$_instance === null) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * @param  string  $tag
     * @return \F9Web\Meta\Meta
     */
    public static function forget(string $tag): self
    {
        if (self::$tags->has($tag)) {
            self::$tags->pull($tag);
        }

        return self::instance();
    }

    /**
     * @return \F9Web\Meta\Meta
     */
    public static function purge(): self
    {
        self::$tags = new Collection();
        self::$rawTags = new Collection();

        return self::instance();
    }

    /**
     * A helper method used within a testing context only.
     * To reset tags use the purge() method.
     *
     * @return \F9Web\Meta\Meta
     * @see Meta::purge() To reset all tags
     */
    public static function resetTags(): self
    {
        self::$tags = null;
        self::$rawTags = [];

        return self::instance();
    }

    /**
     * @param  array  $items
     * @return \F9Web\Meta\Meta
     */
    public static function fromArray(array $items = []): self
    {
        foreach ($items as $key => $value) {
            self::set($key, (string)$value);
        }

        return self::instance();
    }

    /**
     * @param  string  $key
     * @param  string  $value
     * @return \F9Web\Meta\Meta
     */
    public static function set(string $key, string $value): self
    {
        if (self::$tags === null) {
            self::$tags = new Collection();
        }

        self::$tags->put($key, $value);

        return self::instance();
    }

    /**
     * @param  bool  $condition
     * @param  \Closure  $callback
     * @return $this
     */
    public static function when(bool $condition, Closure $callback): self
    {
        $instance = self::instance();

        return tap(
            $instance,
            function ($instance) use ($callback, $condition) {
                return $condition ? $callback($instance) : $instance;
            }
        );
    }

    /**
     * @return array
     */
    public function tags(): array
    {
        if (null === self::$tags) {
            self::$tags = new Collection();
        }

        return self::$tags->concat(self::$rawTags)->toArray();
    }

    /**
     * @return string
     */
    public function toHtml(): string
    {
        return self::render();
    }

    /**
     * @param  string|null  $tag
     * @return string
     */
    public static function render(?string $tag = null): string
    {
        // ensure a meta title is always set
        if (!Arr::get(self::$tags ?? [], 'title')) {
            self::setDefaultTitle();
        }

        // register default tags on each request
        foreach (config('f9web-laravel-meta.defaults') as $key => $value) {
            if (is_int($key)) {
                self::setRawTag($value);
            } else {
                self::set($key, $value);
            }
        }

        $tags = self::$tags;

        // render a specific tag if provided
        if (null !== $tag && isset($tags[$tag])) {
            return (self::getContent($tags[$tag] ?? '', $tag))->toHtml();
        }

        return implode(
            PHP_EOL,
            $tags
                ->map(
                    function ($content, $name) {
                        return self::getContent($content ?? '', $name);
                    }
                )
                ->concat(self::$rawTags)
                ->toArray()
        );
    }

    /**
     * @param  string  $value
     * @param  string  $tag
     * @return HtmlString
     */
    public static function getContent(string $value, string $tag): HtmlString
    {
        $tags = self::$tags;

        // a dedicated tag class with same name as the key exists
        $class = __NAMESPACE__ . '\\Tags\\' . ucwords($tag);

        if (class_exists($class)) {
            return (new $class())->render($tag, $value, $tags);
        }

        // the key starts with "property:" or "og:" - register a property type tag
        if (Str::startsWith($tag, ['property:', 'og:'])) {
            return (new Property())->render($tag, $value, $tags);
        }

        // render a default meta name/content tag
        return (new Name())->render($tag, $value, $tags);
    }

    /**
     * @param $name
     * @return \Illuminate\Support\Collection|string|null
     */
    public function __get($name)
    {
        return self::get($name);
    }

    /**
     * @param  string|null  $name
     * @return \Illuminate\Support\Collection|string|null
     */
    public static function get(?string $name = null)
    {
        if (null === $name) {
            return self::$tags;
        }

        return self::$tags->get($name);
    }

    /**
     * @param $method
     * @param $parameters
     * @return \F9Web\Meta\Meta|null
     */
    public function __call($method, $parameters): ?self
    {
        if (static::hasMacro($method)) {
            return $this->macroCall($method, $parameters);
        }

        if ($method === 'raw') {
            return self::setRawTag($parameters[0]);
        }

        return self::set($method, $parameters[0]);
    }
}
