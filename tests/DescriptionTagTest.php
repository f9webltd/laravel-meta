<?php

namespace F9Web\Meta\Tests;

use function substr;

class DescriptionTagTest extends TestCase
{
    /** @test */
    public function it_renders_the_expected_description_containg_quotes()
    {
        $this->app['config']->set(['f9web-laravel-meta.description-limit' => null]);

        $this->service->set('description', 'We sell 20" industrial nails');

        $this->assertRenders('<meta name="description" content="We sell 20&quot; industrial nails"');

        $this->service->set('description', "This is a good ol' SEO description");

        $this->assertRenders('<meta name="description" content="This is a good ol&#039; SEO description"');
    }

    /** @test */
    public function it_renders_the_expected_description()
    {
        $this->app['config']->set(['f9web-laravel-meta.description-limit' => null]);

        $this->service->set('description', 'some content');

        $this->assertRenders('<meta name="description" content="some content"');
    }

    /** @test */
    public function it_renders_the_expected_description_using_dynamic_function_calls()
    {
        $this->app['config']->set(['f9web-laravel-meta.description-limit' => null]);

        $this->service->description('some content');

        $this->assertRenders('<meta name="description" content="some content"');
    }

    /** @test */
    public function it_renders_the_expected_description_when_a_limit_is_set()
    {
        $this->app['config']->set(['f9web-laravel-meta.description-limit' => $limit = 5]);

        $this->service->set('description', $content = 'Lorem Ipsum is simply dummy text of the printing and typesetting industry');

        $this->assertRenders('<meta name="description" content="' . substr($content, 0, $limit) . '"');
    }
}
