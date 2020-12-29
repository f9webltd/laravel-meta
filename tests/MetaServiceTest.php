<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use Illuminate\Support\Collection;

use function implode;

class MetaServiceTest extends TestCase
{
    /** @test */
    public function it_renders_default_tags(): void
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta.defaults' => [
                    'robots'   => 'all',
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
    public function it_renders_using_fluent_methods(): void
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

        self::assertStringContainsString(
            '<meta name="viewport" content="width=device-width, initial-scale=1.0">',
            $html = $this->service::render()
        );
        self::assertStringContainsString('<meta name="twitter:title" content="twitter title">', $html);
        self::assertStringContainsString('<meta name="description" content="meta description">', $html);
        self::assertStringContainsString('<meta property="title" content="property title">', $html);
        self::assertStringContainsString('<meta property="og:title" content="og property title">', $html);
        self::assertStringContainsString('<title>meta title - Meta Title Append</title>', $html);
        self::assertStringContainsString('<meta name="format-detection" content="telephone=no">', $html);
        self::assertStringContainsString('<meta name="custom" content="value">', $html);
        self::assertStringContainsString('<link rel="pingback" href="https://site.coo.uk/xmlrpc.php" />', $html);
        self::assertStringContainsString('<link rel="canonical" href="/url" />', $html);
        self::assertStringContainsString('<meta name="shortcut icon" content="/icon.png">', $html);
        self::assertStringContainsString('<link rel="icon" type="image/x-icon" href="/icon.png">', $html);
        self::assertStringContainsString('<meta name="robots" content="noindex nofollow">', $html);
        self::assertStringContainsString(
            '<link rel="manifest" href="manifest.json" crossOrigin="use-credentials">',
            $html
        );
        self::assertStringContainsString(
            '<link rel="alternate" type="application/atom+xml" href="https://www.php.net/feed.atom" title="PHP">',
            $html
        );
    }

    /** @test */
    public function it_allows_fetching_of_single_tags(): void
    {
        $this->service::set('canonical', '/users/name');
        $this->service::set('description', 'meta description');

        self::assertEquals('<link rel="canonical" href="/users/name" />', $this->service::render('canonical'));
        self::assertEquals(
            '<meta name="description" content="meta description">',
            $this->service::render('description')
        );
    }

    /** @test */
    public function it_registers_and_renders_raw_tags(): void
    {
        $this->service::setRawTag($ping = '<link rel="pingback" href="https://site.com/xmlrpc.php" />');
        $this->service::setRawTags(
            [
                $alternate = '<link rel="alternate" href="https://site.com/en.php" hreflang="en" />',
                $next = '<link rel="next" href="https://site.com/en-2.php" />',
            ]
        );

        self::assertStringContainsString($ping, collect($this->service->tags())->implode(' '));
        self::assertStringContainsString($alternate, collect($this->service->tags())->implode(' '));
        self::assertStringContainsString($next, collect($this->service->tags())->implode(' '));

        $this->assertRenders(implode(PHP_EOL, [$ping, $alternate, $next]));
    }

    /** @test
     * @throws \Exception
     */
    public function it_can_fetch_all_tags(): void
    {
        $this->service
            ->set('canonical', '/users/name')
            ->set('og:title', 'og title')
            ->set('description', 'meta description')
            ->set('property:custom', 'custom');

        self::assertIsArray($tags = $this->service->tags());

        self::assertArrayHasKey('canonical', $tags);
        self::assertEquals('/users/name', $tags['canonical']);

        self::assertArrayHasKey('og:title', $tags);
        self::assertEquals('og title', $tags['og:title']);

        self::assertArrayHasKey('description', $tags);
        self::assertEquals('meta description', $tags['description']);

        self::assertArrayHasKey('property:custom', $tags);
        self::assertEquals('custom', $tags['property:custom']);
    }

    /** @test */
    public function it_can_forget_tags(): void
    {
        $this->service
            ->set('canonical', '/users/name')
            ->set('description', 'meta description')
            ->forget('description');

        self::assertEquals(['canonical' => '/users/name'], $this->service->tags());

        $this->service::purge();

        self::assertEmpty($this->service->tags());
    }

    /** @test */
    public function it_can_set_tags_dynamically()
    {
        $this->service
            ->canonical('/users/create')
            ->description('meta description');

        self::assertEquals(
            [
                'canonical'   => '/users/create',
                'description' => 'meta description',
            ],
            $this->service->tags()
        );
    }

    /** @test */
    public function it_can_render_tags_using_the_to_html_method(): void
    {
        $this->service::set('canonical', '/users');

        self::assertStringContainsString('<link rel="canonical" href="/users" />', $this->service->toHtml());
    }

    /** @test */
    public function it_can_determine_tags_dynamically(): void
    {
        $this->service::set('canonical', '/users');

        self::assertEquals('/users', $this->service->canonical);
    }

    /** @test */
    public function it_get_all_tags_when_calling_with_parameters(): void
    {
        $this->service::set('canonical', '/users');
        $this->service::set('title', 'meta title');

        self::assertInstanceOf(Collection::class, $this->service->get());
    }

    /** @test */
    public function it_get_all_tags_when_no_standard_tags_have_been_set(): void
    {
        $this->service::setRawTag('<tag />');

        self::assertNotEmpty($this->service->tags());
    }

    /** @test */
    public function it_gets_all_tags_when_no_raw_tags_have_been_set(): void
    {
        $this->service::set('canonical', '/users');

        self::assertTrue(isset($this->service->tags()['canonical']));
    }

    /** @test */
    public function it_gets_all_tags_when_none_are_present(): void
    {
        self::assertIsArray($this->service->tags());
    }

    /** @test */
    public function it_handles_calls_to_tags_when_no_tags_are_set(): void
    {
        $this->service::resetTags();

        self::assertCount(0, $this->service->tags());

        $this->service::set('a', 'b');

        self::assertCount(1, $this->service->tags());
    }

    /** @test */
    public function it_handles_calls_to_tags_when_no_raw_tags_are_set(): void
    {
        $this->service::resetTags();

        self::assertCount(0, $this->service->tags());

        $this->service::setRawTag('<link rel="something" href="/users" />');

        self::assertCount(1, $this->service->tags());
    }

    /** @test */
    public function it_handles_calls_to_set_tags_when_no_tags_are_set(): void
    {
        $this->service::resetTags();

        $this->service::set('a', 'b');

        self::assertCount(1, $this->service->tags());
    }
}
