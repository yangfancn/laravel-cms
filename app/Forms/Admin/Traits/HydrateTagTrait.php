<?php

namespace App\Forms\Admin\Traits;

use Illuminate\Database\Eloquent\Model;

trait HydrateTagTrait
{
    protected static function hydrateTag(Model $model): array
    {
        return ['tags' => $model->tags->pluck('id')->all()];
    }
}
