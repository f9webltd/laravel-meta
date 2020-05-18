<?php

namespace F9Web\Meta\Tests;

class BladeDirectiveTest extends TestCase
{
    /**
     * @test
     * @dataProvider bladeDataProvider
     * @param  string  $expected
     * @param  string  $directive
     */
    public function it_compiles_without_arguments(string $expected, string $directive)
    {
        $compiled = $this->app['blade.compiler']->compileString($directive);

        $this->assertSame($expected, $compiled);
    }

    /**
     * @return array|\string[][]
     */
    public function bladeDataProvider(): array
    {
        return [
            [
                '<?php echo meta()->render(); ?>',
                '@meta',
            ],
            [
                '<?php echo meta()->render(\'og:title\'); ?>',
                '@meta(\'og:title\')',
            ],
        ];
    }
}
