<?php

namespace F9Web\Meta\Tests;

class CanonicalTagTest extends TestCase
{
    /** @test */
    public function it_renders_the_expected_raw_tag()
    {
        $this->service->set('canonical', '/users/profile/abc');

        $this->assertRenders('<link rel="canonical" href="/users/profile/abc" />');
    }

    /**
     * @test
     * @dataProvider canonicalUrlsProvider
     * @param  string  $actual
     * @param  string  $expected
     */
    public function it_renders_the_expected_adjusted_tag_url(string $actual, string $expected)
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta.removable-uri-segments' => [
                    '/public',
                    '/index.php',
                    '/exclude-me',
                ],
            ]
        );

        $this->service->canonical($actual);

        $this->assertRenders(
            '<link rel="canonical" href="'.$expected.'" />',
            "Set {$actual}, Expected {$actual}"
        );
    }

    /**
     * @return array|\string[][]
     */
    public function canonicalUrlsProvider(): array
    {
        return [
            ['/users/profile/abc', '/users/profile/abc'],
            ['https://www.site.co.uk/users/profile/abc', 'https://www.site.co.uk/users/profile/abc'],
            ['/users/123/', '/users/123/'],
            ['/public/users/123/', '/users/123/'],
            ['/index.php/users/123/', '/users/123/'],
            ['/users/index.php/slug/123/', '/users/slug/123/'],
            ['/public/index.php/users/slug/123/', '/users/slug/123/'],
            ['/public/index.php/users/slug/123/', '/users/slug/123/'],
            ['https://site.co.uk/public/index.php/users/slug/123/', 'https://site.co.uk/users/slug/123/'],
            ['https://site.co.uk/exclude-me/users/slug/123/', 'https://site.co.uk/users/slug/123/'],
        ];
    }
}
