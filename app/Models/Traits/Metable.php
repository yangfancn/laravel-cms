<?php

namespace App\Models\Traits;

use App\Models\Meta;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Metable
{
    public function meta(): MorphOne
    {
        return $this->morphOne(Meta::class, 'metable');
    }

    protected static function bootMetable(): void
    {
        static::saved(function (self $model) {
            if ($meta = \request()->post('meta')) {
                $model->meta()->updateOrCreate([], $meta);
            }
            if (
                $model->meta
                && ! $model->meta->title
                && ! $model->meta->keywords
                && ! $model->meta->description
                && ! $model->meta->others
            ) {
                $model->meta->delete();
            }
        });
    }
}
