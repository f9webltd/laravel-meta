<?php

namespace F9Web\Meta\Tests;

use F9Web\Meta\Meta;
use F9Web\Meta\MetaFacade;

class FacadeTest extends TestCase
{
    /** @test */
    public function it_passes_calls_to_the_container()
    {
        $this->mock(
            Meta::class,
            function ($mock) {
                $mock->shouldReceive('set')->once()->with('title', 'the meta title');
                $mock->shouldReceive('set')->once()->with('description', 'the meta description');
                $mock->shouldReceive('setRawTag')->once()->with(
                    '<link rel="search" type="application/d+xml" title="Stack Overflow" href="/s.xml">'
                );
            }
        );

        MetaFacade::set('title', 'the meta title')
            ->set('description', 'the meta description')
            ->setRawTag('<link rel="search" type="application/d+xml" title="Stack Overflow" href="/s.xml">');
    }
}
