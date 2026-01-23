<?php

namespace App\Forms\Admin\Traits;

use Illuminate\Database\Eloquent\Model;

trait HydrateSlugTrait
{
    protected static function hydrateSlug(Model $model): array
    {
        return ['slug' => $model->slug?->name];
    }
}
