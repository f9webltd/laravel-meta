<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use DMS\PHPUnitExtensions\ArraySubset\Assert;
use DMS\PHPUnitExtensions\ArraySubset\ArraySubsetAsserts;

use function implode;

class MetaServiceTest extends TestCase
{
    use ArraySubsetAsserts;

    /** @test */
    public function it_renders_default_tags()
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta.defaults' => [
                    'robots' => 'all',
                    'referrer' => 'no-referrer-when-downgrade',
                ],
            ]
        );

        $this->assertRenders(
            implode(
                PHP_EOL,
                [
                    '<meta name="robots" content="all">',
                    '<meta name="referrer" content="no-referrer-when-downgrade">',
                ]
            )
        );
    }

    /** @test */
    public function it_renders_using_fluent_methods()
    {
        $this->service
            ->favIcon('/icon.png')
            ->noIndex()
            ->canonical('/public/url')
            ->set('viewport', 'width=device-width, initial-scale=1.0')
            ->set('twitter:title', 'twitter title')
            ->set('property:title', 'property title')
            ->set('og:title', 'og property title')
            ->title('meta title')
            ->description('meta description')
            ->fromArray(
                [
                    'format-detection' => 'telephone=no',
                    'custom'           => 'value',
                ]
            )
            ->raw('<link rel="pingback" href="https://site.coo.uk/xmlrpc.php" />')
            ->setRawTags(
                [
                    '<link rel="alternate" type="application/atom+xml" href="https://www.php.net/feed.atom" title="PHP">',
                    '<link rel="manifest" href="manifest.json" crossOrigin="use-credentials">',
                ]
            );

        $this->assertStringContainsString(
            '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
            $html = $this->service->render()
        );
        $this->assertStringContainsString('<meta name="twitter:title" content="twitter title">', $html);
        $this->assertStringContainsString('<meta name="description" content="meta description">', $html);
        $this->assertStringContainsString('<meta property="title" content="property title">', $html);
        $this->assertStringContainsString('<meta property="og:title" content="og property title">', $html);
        $this->assertStringContainsString('<title>meta title - Meta Title Append</title>', $html);
        $this->assertStringContainsString('<meta name="format-detection" content="telephone=no">', $html);
        $this->assertStringContainsString('<meta name="custom" content="value">', $html);
        $this->assertStringContainsString('<link rel="pingback" href="https://site.coo.uk/xmlrpc.php" />', $html);
        $this->assertStringContainsString('<link rel="canonical" href="/url" />', $html);
        $this->assertStringContainsString('<meta name="shortcut icon" content="/icon.png">', $html);
        $this->assertStringContainsString('<link rel="icon" type="image/x-icon" href="/icon.png">', $html);
        $this->assertStringContainsString('<meta name="robots" content="noindex nofollow">', $html);
        $this->assertStringContainsString('<link rel="manifest" href="manifest.json" crossOrigin="use-credentials">', $html);
        $this->assertStringContainsString(
            '<link rel="alternate" type="application/atom+xml" href="https://www.php.net/feed.atom" title="PHP">',
            $html
        );
    }

    /** @test */
    public function it_allows_fetching_of_single_tags()
    {
        $this->service->set('canonical', '/users/name');
        $this->service->set('description', 'meta description');

        $this->assertEquals('<link rel="canonical" href="/users/name" />', $this->service->render('canonical'));
        $this->assertEquals(
            '<meta name="description" content="meta description">',
            $this->service->render('description')
        );
    }

    /** @test */
    public function it_registers_and_renders_raw_tags()
    {
        $this->service->setRawTag($ping = '<link rel="pingback" href="https://site.com/xmlrpc.php" />');
        $this->service->setRawTags(
            [
                $alternate = '<link rel="alternate" href="https://site.com/en.php" hreflang="en" />',
                $next = '<link rel="next" href="https://site.com/en-2.php" />',
            ]
        );

        $this->assertStringContainsString($ping, collect($this->service->tags())->implode(' '));
        $this->assertStringContainsString($alternate, collect($this->service->tags())->implode(' '));
        $this->assertStringContainsString($next, collect($this->service->tags())->implode(' '));

        $this->assertRenders(implode(PHP_EOL, [$ping, $alternate, $next]));
    }

    /** @test
     * @throws \Exception
     */
    public function it_can_fetch_all_tags()
    {
        $this->service
            ->set('canonical', '/users/name')
            ->set('og:title', 'og title')
            ->set('description', 'meta description')
            ->set('property:custom', 'custom');

        $this->assertIsArray($tags = $this->service->tags());

        Assert::assertArraySubset(['canonical' => '/users/name'], $tags);
        Assert::assertArraySubset(['og:title' => 'og title'], $tags);
        Assert::assertArraySubset(['description' => 'meta description'], $tags);
        Assert::assertArraySubset(['property:custom' => 'custom'], $tags);
    }

    /** @test */
    public function it_can_forget_tags()
    {
        $this->service
            ->set('canonical', '/users/name')
            ->set('description', 'meta description')
            ->forget('description');

        $this->assertEquals(['canonical' => '/users/name'], $this->service->tags());

        $this->service->purge();

        $this->assertEmpty($this->service->tags());
    }

    /** @test */
    public function it_can_set_tags_dynamically()
    {
        $this->service
            ->canonical('/users/create')
            ->description('meta description');

        $this->assertEquals([
            'canonical' => '/users/create',
            'description' => 'meta description',
        ], $this->service->tags());
    }
}
