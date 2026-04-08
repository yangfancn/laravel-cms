<?php

namespace App\Models\Traits;

use App\Models\Meta;
use Exception;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\DB;

trait Metable
{
    protected ?array $metaPayload = null;

    public function initializeMetable(): void
    {
        $this->mergeFillable(['meta']);
    }

    public function setMetaAttribute(array $meta): void
    {
        $this->metaPayload = $meta;
    }

    public function meta(): MorphOne
    {
        return $this->morphOne(Meta::class, 'metable');
    }

    protected static function bootMetable(): void
    {
        static::saving(fn () => DB::beginTransaction());
        static::saved(function (self $model) {
            if ($model->metaPayload) {
                try {
                    $model->meta()->updateOrCreate([], $model->metaPayload);
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
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
            DB::commit();
            $model->metaPayload = null;
        });
    }
}
