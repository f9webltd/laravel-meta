<?php

namespace F9Web\Meta\Tests;

class MetaNameTagTest extends TestCase
{
    /**
     * @test
     * @dataProvider metaNameTagsProvider
     * @param  string  $key
     * @param  string  $value
     */
    public function it_renders_the_expected_tags(string $key, string $value)
    {
        $this->service->set($key, $value);

        $this->assertRenders('<meta name="' . $key . '" content="' . $value . '">');
    }

    /**
     * @return array|\string[][]
     */
    public static function metaNameTagsProvider(): array
    {
        return [
                ['description', 'Laravel documentation'],
                ['viewport', 'width=device-width, initial-scale=1.0'],
                ['twitter:title', 'My Twitter Card Title'],
                ['theme-color', '#cc0000'],
                ['shortcut icon', 'https://site.co.uk/icon.png'],
                ['robots', 'noindex nofollow'],
                ['twitter:image:height', '1200'],
                ['twitter:site', 'laravel'],
                ['twitter:site:id', '123456789'],
                ['google-analytics', 'UA-12345678-2'],
                ['browser-stats-url', 'https://api.github.com/_private/browser/stats'],
                ['enabled-features', 'MARKETPLACE_PENDING_INSTALLATIONS,GHE_CLOUD_TRIAL,PAGE_STALE_CHECK'],
        ];
    }
}
