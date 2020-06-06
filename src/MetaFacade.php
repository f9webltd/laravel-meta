<?php

namespace F9Web\Meta;

use Illuminate\Support\Facades\Facade;

/**
 * @see \F9Web\Meta\Meta
 * @method static \F9Web\Meta\Meta noIndex()
 * @method static \F9Web\Meta\Meta favIcon(string $src = null)
 * @method static void setDefaultTitle()
 * @method static ?string guessTitle()
 * @method static \F9Web\Meta\Meta set(string $key, string $value)
 * @method static \F9Web\Meta\Meta setRawTag(string $value)
 * @method static \F9Web\Meta\Meta setRawTags(array $tags = [])
 * @method static ?\F9Web\Meta\Meta instance(array $tags = [])
 * @method static \F9Web\Meta\Meta forget(string $tag)
 * @method static \F9Web\Meta\Meta purge()
 * @method static \F9Web\Meta\Meta fromArray(array $items = [])
 * @method static \F9Web\Meta\Meta when(bool $condition, \Closure $callback)
 * @method static array tags()
 * @method static string toHtml()
 * @method static string render(string $tag = null)
 * @method static \Illuminate\Support\HtmlString getContent(string $value, string $tag)
 * @method static \Illuminate\Support\Collection|string|null get(string $name = null)
 */
class MetaFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'meta';
    }
}
