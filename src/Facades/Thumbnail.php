<?php

namespace Thumbnail\Facades;


use Illuminate\Support\Facades\Facade;

/**
 * @method static \Thumbnail\Thumbnail src(string $path, string $disk = null)
 * @method static \Thumbnail\Thumbnail preset(string $preset)
 */
class Thumbnail extends Facade
{
    protected static function getFacadeAccessor()
    {
        return static::$app[\Thumbnail\Thumbnail::class];
    }
}
