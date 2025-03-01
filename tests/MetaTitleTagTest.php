<?php

namespace F9Web\Meta\Tests;

class MetaTitleTagTest extends TestCase
{
    /** @test */
    public function it_renders_the_expected_title_containg_quotes()
    {
        $this->app['config']->set(['f9web-laravel-meta.description-limit' => null]);

        $this->service->set('title', 'We sell 20" industrial nails');

        $this->assertRenders('<title>We sell 20&quot; industrial nails - Meta Title Append</title>');

        $this->service->set('title', "This is a good ol' SEO description");

        $this->assertRenders('<title>This is a good ol&#039; SEO description - Meta Title Append</title>');
    }

    /** @test */
    public function it_renders_with_an_appended_title()
    {
        $this->app['config']->set(['f9web-laravel-meta.meta-title-append' => 'AcmeLtd']);

        $this->service->set('title', 'Widgets, Best Widgets');

        $this->assertRenders('<title>Widgets, Best Widgets - AcmeLtd</title>');
    }

    /** @test */
    public function it_renders_without_an_appended_title()
    {
        $this->app['config']->set(['f9web-laravel-meta.meta-title-append' => null]);

        $this->service->set('title', 'Widgets, Best Widgets');

        $this->assertRenders('<title>Widgets, Best Widgets</title>');
    }

    /** @test */
    public function it_renders_a_none_limited_title_length()
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta.title-limit'       => null,
                'f9web-laravel-meta.meta-title-append' => null,
            ]
        );

        // given a title 26 characters in length is set, without a limit
        $this->service->set('title', 'abcdefghijklmnopqrstuvwxyz');

        // the full title should be present
        $this->assertRenders('<title>abcdefghijklmnopqrstuvwxyz</title>');
    }

    /** @test */
    public function it_renders_a_limited_title_length()
    {
        // given the title is limited to 10 characters ...
        $this->app['config']->set(
            [
                'f9web-laravel-meta.title-limit'       => 5,
                'f9web-laravel-meta.meta-title-append' => null,
            ]
        );

        $this->service->set('title', 'abcdefghij');

        // the limited title should be present
        $this->assertRenders('<title>abcde</title>');
    }

    /** @test */
    public function it_renders_the_default_title_when_one_is_not_set()
    {
        // given the title is not set and a default exists
        $this->app['config']->set(
            [
                'f9web-laravel-meta.fallback-meta-title' => 'App Name',
                'f9web-laravel-meta.meta-title-append'   => null,
            ]
        );

        // the full title should be present
        $this->assertRenders('<title>App Name</title>');
    }

    /** @test */
    public function it_renders_an_empty_title_when_one_is_not_set_and_no_default_exists()
    {
        $this->app['config']->set(
            [
                'f9web-laravel-meta.fallback-meta-title' => null,
                'f9web-laravel-meta.meta-title-append'   => null,
            ]
        );

        $this->assertRenders('<title></title>');
    }

    /** @test */
    public function it_renders_the_expected_title_when_hyphens_are_present()
    {
        // given the title is not set and a default exists
        $this->app['config']->set(
            [
                'f9web-laravel-meta.fallback-meta-title' => 'App Name',
                'f9web-laravel-meta.meta-title-append'   => null,
            ]
        );

        // the full title should be present
        $this->assertRenders('<title>App Name</title>');
    }
}
