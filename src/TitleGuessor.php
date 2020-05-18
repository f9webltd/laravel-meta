<?php

namespace F9Web\Meta;

use function collect;
use function config;
use function explode;
use Illuminate\Support\Collection;
use function in_array;
use function parse_url;
use function str_replace;
use function ucwords;

class TitleGuessor
{
    /** @var string */
    protected $method = 'uri';

    /** @var string */
    protected $uri;

    /** @var null|string */
    protected $route = null;

    /**
     * @return null|string
     */
    public function render()
    {
        $config = config('f9web-laravel-meta.title-guessor');

        if (! $config['enabled']) {
            return null;
        }

        if (! in_array($method = $config['method'], ['route', 'uri'])) {
            $method = 'uri';
        }

        if ($method === 'uri' && $this->uri !== null) {
            return $this->getUriSegments()->map(
                function ($segment) {
                    return ucwords($segment);
                }
            )->implode(' - ');
        }

        if ($method === 'route' && ($routeName = $this->route)) {
            $routeName = str_replace('.index', '', $routeName);

            return ucwords(str_replace('.', ' - ', $routeName));
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getUriSegments(): Collection
    {
        return collect(explode('/', parse_url($this->uri)['path']))->filter();
    }

    /**
     * @param  string  $uri
     * @return $this
     */
    public function withUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    /**
     * @param  string|null  $route
     * @return $this
     */
    public function withRoute(?string $route = null): self
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    public function reset(): self
    {
        $this->method = 'uri';
        $this->route = null;

        return $this;
    }
}
