<?php

namespace App\Forms\Admin\Traits;

use Illuminate\Database\Eloquent\Model;

trait HydrateMetaTrait
{
    protected static function hydrateMeta(Model $model): array
    {
        return ['meta' => $model->meta?->toArray()];
    }
}
