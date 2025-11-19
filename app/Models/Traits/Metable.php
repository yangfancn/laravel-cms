<?php

namespace App\Models\Traits;

use App\Models\Meta;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

trait Metable
{
    public function meta(): MorphOne
    {
        return $this->morphOne(Meta::class, 'metable');
    }

    protected static function bootMetable(): void
    {
        static::saving(fn () => DB::beginTransaction());
        static::saved(function (self $model) {
            if ($meta = \request()->post('meta')) {
                try {
                    $model->meta()->updateOrCreate([], $meta);
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
                DB::commit();
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
