<?php

namespace F9Web\Meta;

use F9Web\Meta\Exceptions\GuessorException;
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
    public $method = 'uri';

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

        $this->setMethod($config['method']);

        if (! in_array($method = $this->getMethod(), ['route', 'uri'])) {
            throw new GuessorException();
        }

        if ($method === 'uri' && $this->uri !== null) {
            return $this->getFromUri();
        }

        return $this->getFromRoute();
    }

    /**
     * @return string|null
     */
    private function getFromRoute(): ?string
    {
        $routeName = str_replace('.index', '', $this->route ?? '');

        return ucwords(str_replace('.', ' - ', $routeName));
    }

    /**
     * @return string|null
     */
    private function getFromUri(): ?string
    {
        return $this->getUriSegments()->map(
            function ($segment) {
                return ucwords($segment);
            }
        )->implode(' - ');
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
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    /**
     * @param  string|null  $method
     * @return $this
     */
    public function setMethod(?string $method = null): self
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return $this
     */
    public function reset(): self
    {
        $this->method = 'uri';
        $this->route = null;

        return $this;
    }
}
