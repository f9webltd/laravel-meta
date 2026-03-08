<?php

declare(strict_types=1);

namespace F9Web\Meta\Tests;

use PHPUnit\Framework\Attributes\DataProvider;

class MetaPropertyTagTest extends TestCase
{
    #[DataProvider('metaPropertyTagsProvider')]
    public function test_it_renders_open_graph_tags(array $data, string $expected)
    {
        $this->service->set($data[0], $data[1]);

        $this->assertRenders($expected);
    }

    /**
     * @return array|\string[][]
     */
    public static function metaPropertyTagsProvider(): array
    {
        return [
            [
                ['property:fb:app_id', '123456'],
                '<meta property="fb:app_id" content="123456">',
            ],
            [
                ['property:og:url', '/users'],
                '<meta property="og:url" content="/users">',
            ],
            [
                ['og:image:width', '1200'],
                '<meta property="og:image:width" content="1200">',
            ],
            [
                ['og:title', 'Laravel Docs'],
                '<meta property="og:title" content="Laravel Docs">',
            ],
            [
                ['property:title', 'Laravel Documentation'],
                '<meta property="title" content="Laravel Documentation">',
            ],
        ];
    }
}
