<?php

namespace F9Web\Meta\Tests;

use F9Web\Meta\Meta;

class MacroableTest extends TestCase
{
    /** @test */
    public function it_allows_macroable_methods()
    {
        Meta::macro('pageNotFound', function () {
            return Meta::noIndex()
                ->set('title', 'Not Found')
                ->set('description', 'This page cannot be found, sorry!');
        });

        $this->service
            ->set('og:title', 'the og title')
            ->pageNotFound();

        $this->assertCount(4, $this->service->tags());

        $this->assertRenders('<title>Not Found - Meta Title Append</title>');
        $this->assertRenders('<meta name="description" content="This page cannot be found, sorry!">');
        $this->assertRenders('<meta name="robots" content="noindex nofollow">');
        $this->assertRenders('<meta property="og:title" content="the og title">');
    }

    /** @test */
    public function it_allows_for_macroable_methods_with_arguments()
    {
        Meta::macro('setSomethingUnnecessarily', function ($name, $content) {
            return Meta::set($name, $content)->noIndex();
        });

        $this->service->setSomethingUnnecessarily('og:title', 'og title, cool');

        $this->assertCount(2, $this->service->tags());

        $this->assertRenders('<meta property="og:title" content="og title, cool">');
        $this->assertRenders('<meta name="robots" content="noindex nofollow">');
    }

    /** @test */
    public function it_allows_for_macroable_methods_with_arguments_and_internal_logic()
    {
        Meta::macro('setPaginationTags', function (array $data) {
            $page = $data['page'] ?? 1;

            if ($page > 1) {
                Meta::setRawTag('<link rel="prev" href="' . $data['prev'] . '" />');
            }

            if (!empty($data['next'])) {
                return Meta::setRawTag('<link rel="next" href="' . $data['next'] . '" />');
            }

            return Meta::instance();
        });

        $this->service->setPaginationTags([
            'page' => 7,
            'next' => '/users/page/8',
            'prev' => '/users/page/6',
        ]);

        $this->assertCount(2, $this->service->tags());
        $this->service->noIndex();
        $this->assertCount(3, $this->service->tags());

        $this->assertRenders('<link rel="prev" href="/users/page/6" />');
        $this->assertRenders('<link rel="next" href="/users/page/8" />');

        $this->service->purge();
        $this->assertCount(0, $this->service->tags());

        $this->service->setPaginationTags([
              'page' => 1,
              'next' => '/users/page/2',
          ]);

        $this->assertNotRenders('<link rel="prev" href="/users/page/1" />');
        $this->assertRenders('<link rel="next" href="/users/page/2" />');
    }
}
