<?php

namespace App\Facades;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Facade;

/**
 * @method static self model(?Model $model = null)
 * @method static self seo(string $title, ?string $keywords = null, ?string $description = null, ?string $url = null, ?string $image = null)
 * @method static self withoutSiteName()
 * @method static self noindex()
 * @method static string generate()
 */
class Seo extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\Seo::class;
    }
}
