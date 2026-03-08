<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use F9Web\Meta\Exceptions\GuessorException;
use F9Web\Meta\TitleGuessor;
use PHPUnit\Framework\Attributes\DataProvider;

class TitleGuessorTest extends TestCase
{
    public function tearDown(): void
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta' => [
                    'title-guessor' => [
                        'enabled' => true,
                        'method'  => 'uri',
                    ],
                ],
            ]
        );

        parent::tearDown();
    }

    public function test_it_resets_class_properties()
    {
        $service = new TitleGuessor();
        $service->withRoute('users.create');

        $this->assertEquals('uri', $service->getMethod());
        $this->assertEquals('users.create', $service->getRoute());

        $service->reset();

        $this->assertEquals('uri', $service->getMethod());
        $this->assertNull($service->getRoute());
    }

    public function test_it_returns_null_when_disabled()
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta' => [
                    'title-guessor' => [
                        'enabled' => false,
                    ],
                ],
            ]
        );

        $this->assertNull((new TitleGuessor())->render());
    }

    public function test_it_throws_an_exception__when_an_invalid_guessing_method_is_provided()
    {
        $this->expectException(GuessorException::class);

        $this->app['config']->set(
            [
                'f9web-laravel-meta.title-guessor.method'  => 'not-route-or-uri',
            ]
        );

        (new TitleGuessor())->render();
    }

    #[DataProvider('uriSegmentsProvider')]
    public function test_it_determines_the_title_using_uri_segments(string $title, string $uri)
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta' => [
                    'title-guessor' => [
                        'enabled' => true,
                        'method'  => 'uri',
                    ],
                ],
            ]
        );

        $service = (new TitleGuessor());

        $this->assertEquals($title, $service->withUri($uri)->render());
    }

    /**
     * @return array|\string[][]
     */
    public static function uriSegmentsProvider(): array
    {
        return [
            ['Users - 23 - Edit', 'https://www.site.co.uk/users/23/edit'],
            ['Site.io - Users - 23 - Edit', 'site.io/users/23/edit'],
            ['Users - 23 - Edit', '/users/23/edit'],
            ['Orders - Create', '/orders/create'],
            ['Users - 999', '/users/999'],
            ['Users - 999', '/users/999'],
            ['Users', '/users'],
            ['Products - Software - Computing - Algorithms', '/products/software/computing/algorithms'],
            ['', '/www.baidu.com:443'],
            ['', '/ios.prod.ftl.netflix.com:443'],
            ['', 'ip.ws.126.net:443'],
        ];
    }

    #[DataProvider('namedRoutesProvider')]
    public function test_it_determines_the_title_using_named_routes(string $title, string $route)
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta' => [
                    'title-guessor' => [
                        'enabled' => true,
                        'method'  => 'route',
                    ],
                ],
            ]
        );

        $service = (new TitleGuessor());

        $this->assertEquals($title, $service->withRoute($route)->render());
    }

    /**
     * @return array|\string[][]
     */
    public static function namedRoutesProvider(): array
    {
        return [
            ['Users - Create', 'users.create'],
            ['Users - Edit', 'users.edit'],
            ['Users', 'users.index'],
            ['Products - Show', 'products.show'],
            ['Products - Categories - Show', 'products.categories.show'],
        ];
    }
}
