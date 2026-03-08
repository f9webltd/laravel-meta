<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use PHPUnit\Framework\Attributes\DataProvider;

class BladeDirectiveTest extends TestCase
{
    #[DataProvider('bladeDataProvider')]
    public function test_it_compiles_without_arguments(string $expected, string $directive)
    {
        $compiled = $this->app['blade.compiler']->compileString($directive);

        $this->assertSame($expected, $compiled);
    }

    /**
     * @return array|\string[][]
     */
    public static function bladeDataProvider(): array
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
