<?php

namespace F9Web\Meta\Tests;

use F9Web\Meta\Meta;

class HelperTest extends TestCase
{
    /** @test */
    public function it_passes_calls_to_the_container()
    {
        $this->mock(
            Meta::class,
            function ($mock) {
                $mock->shouldReceive('set')
                    ->once()
                    ->with('title', 'the meta title')
                    ->andReturnSelf();

                $mock->shouldReceive('get')
                    ->twice()
                    ->with('title');
            }
        );

        meta()->set('title', 'the meta title');
        meta()->get('title');
    }
}
