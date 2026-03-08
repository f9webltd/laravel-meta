<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use F9Web\Meta\Meta;

class HelperTest extends TestCase
{
    public function test_it_passes_calls_to_the_container()
    {
        $this->mock(
            Meta::class,
            function ($mock) {
                $mock->shouldReceive('set')
                    ->once()
                    ->with('title', 'the meta title')
                    ->andReturnSelf();

                $mock->shouldReceive('get')
                    ->once()
                    ->with('title');
            }
        );

        meta()->set('title', 'the meta title');
        meta()->get('title');
    }
}
