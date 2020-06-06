<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

class MetaServiceWhenTest extends TestCase
{
    /** @test */
    public function it_sets_tags_when_the_condition_is_true()
    {
        $this->service->set('description', 'hello');

        $this->service->when(
            true,
            function ($meta) {
                $meta->noIndex();
                $meta->set('og:title', 'the og title');
            }
        );

        $this->assertCount(3, $this->service->tags());

        $this->assertRenders('<meta name="description" content="hello">');
        $this->assertRenders('<meta property="og:title" content="the og title">');
        $this->assertRenders('<meta name="robots" content="noindex nofollow">');
    }

    /** @test */
    public function it_does_not_set_tags_when_the_condition_is_false()
    {
        $this->assertCount(0, $this->service->tags());

        $this->service->set('canonical', '/users');

        $this->service->when(
            false,
            function ($meta) {
                $meta->noIndex();
            }
        );

        $this->assertCount(1, $this->service->tags());

        $this->assertNotRenders('<meta name="robots" content="noindex nofollow">');
        $this->assertRenders('<link rel="canonical" href="/users" />');
    }

    /** @test */
    public function it_allows_fluent_calls()
    {
        $this->assertCount(0, $this->service->tags());

        $this->service
            ->set('canonical', '/users/@roballport')
            ->when(
                false,
                function ($meta) {
                    $meta->noIndex();
                }
            )
            ->when(
                true,
                function ($meta) {
                    $meta->set('og:title', 'the og title');
                }
            )
            ->when(
                false,
                function ($meta) {
                    $meta->set('og:description', 'the og description');
                }
            )
            ->when(
                true,
                function ($meta) {
                    $meta->setRawTag('<meta charset="utf-8">');
                }
            )
            ->set('title', 'meta title');

        $this->assertCount(4, $this->service->tags());

        $this->assertRenders('<link rel="canonical" href="/users/@roballport" />');
        $this->assertNotRenders('<meta name="robots" content="noindex nofollow">');
        $this->assertRenders('<meta property="og:title" content="the og title">');
        $this->assertRenders('<meta charset="utf-8">');
        $this->assertRenders('<title>meta title - Meta Title Append</title>');
    }
}
