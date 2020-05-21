<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use Illuminate\Support\Collection;

use function implode;

class MetaServiceTest extends TestCase
{
    /** @test */
    public function it_renders_default_tags()
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta.defaults' => [
                    'robots' => 'all',
                    'referrer' => 'no-referrer-when-downgrade',
                    '<meta name="format-detection" content="telephone=no">',
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

        $this->assertArrayHasKey('canonical', $tags);
        $this->assertEquals('/users/name', $tags['canonical']);

        $this->assertArrayHasKey('og:title', $tags);
        $this->assertEquals('og title', $tags['og:title']);

        $this->assertArrayHasKey('description', $tags);
        $this->assertEquals('meta description', $tags['description']);

        $this->assertArrayHasKey('property:custom', $tags);
        $this->assertEquals('custom', $tags['property:custom']);
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

    /** @test */
    public function it_can_render_tags_using_the_to_html_method()
    {
        $this->service->set('canonical', '/users');

        $this->assertStringContainsString('<link rel="canonical" href="/users" />', $this->service->toHtml());
    }

    /** @test */
    public function it_can_determine_tags_dynamically()
    {
        $this->service->set('canonical', '/users');

        $this->assertEquals('/users', $this->service->canonical);
    }

    /** @test */
    public function it_get_all_tags_when_calling_with_parameters()
    {
        $this->service->set('canonical', '/users');
        $this->service->set('title', 'meta title');

        $this->assertInstanceOf(Collection::class, $this->service->get());
    }

    /** @test */
    public function it_get_all_tags_when_no_standard_tags_have_been_set()
    {
        $this->service->setRawTag('<tag />');

        $this->assertNotEmpty($this->service->tags());
    }

    /** @test */
    public function it_gets_all_tags_when_no_raw_tags_have_been_set()
    {
        $this->service->set('canonical', '/users');

        $this->assertTrue(isset($this->service->tags()['canonical']));
    }

    /** @test */
    public function it_gets_all_tags_when_none_are_present()
    {
        $this->assertIsArray($this->service->tags());
    }
}
