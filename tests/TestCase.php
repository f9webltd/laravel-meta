<?php

namespace F9Web\Meta\Tests;

use function asset;
use F9Web\Meta\Meta;
use F9Web\Meta\MetaServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use function resolve;

abstract class TestCase extends OrchestraTestCase
{
    /** @var \F9Web\Meta\Meta */
    protected $service;

    public function setUp(): void
    {
        parent::setUp();

        $this->service = resolve(Meta::class);
    }

    public function tearDown(): void
    {
        $this->service->purge();

        $this->app['config']->set(
            [
                'f9web-laravel-meta' => [
                    'defaults'               => [],
                    'meta-title-append'      => null,
                    'title-guessor'          => [
                        'enabled' => true,
                        'method'  => 'route',
                    ],
                    'removable-uri-segments' => [
                        '/public',
                        '/index.php',
                    ],
                    'fallback-meta-title'    => null,
                    'favicon-path'           => asset('favicon.ico'),
                    'title-limit'            => 60,
                    'description-limit'      => null,
                ],
            ]
        );

        parent::tearDown();
    }

    /**
     * @param  string  $expected
     * @param  string  $message
     */
    public function assertRenders(string $expected, $message = '')
    {
        $this->assertStringContainsString($expected, $this->service->render(), $message);
    }

    /**
     * @param  \Illuminate\Foundation\Application  $app
     * @return array|string[]
     */
    protected function getPackageProviders($app): array
    {
        return [
            MetaServiceProvider::class,
        ];
    }
}
