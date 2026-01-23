<?php

namespace App\Models\Traits;

use App\Models\Slug;
use DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Str;

trait Sluggable
{
    protected ?string $slugPayload = null;

    public string $separator = '-';

    public function initializeSluggable(): void
    {
        if (! empty($this->fillable)) {
            $this->mergeFillable(['slug']);
        }
    }

    public function setSlugAttribute(?string $slug): void
    {
        $this->slugPayload = $slug;
    }

    public function slug(): MorphOne
    {
        return $this->morphOne(Slug::class, 'sluggable');
    }

    protected static function bootSluggable(): void
    {
        static::saving(fn () => DB::beginTransaction());

        static::saved(function (self $model) {
            try {
                if (! $slug = $model->slugPayload) {
                    $slugBy = $model->slugBy ?: ['name'];

                    $slug = collect($slugBy)
                        ->map(
                            fn ($f) => data_get($model, $f)
                            |> trim(...)
                        )
                        ->implode(' ')
                        |> (fn ($str) => Str::slug($str, $model->separator));

                }

                // check exists
                $count = Slug::where('name', $slug)
                    ->when($model->slug, fn ($q) => $q->whereNot('id', $model->slug->id))
                    ->count();

                if ($count) {
                    $count++;
                    $slug .= "{$model->separator}{$count}";
                }
                $model->slug()->exists()
                    ? $model->slug()->update(['name' => $slug])
                    : $model->slug()->create(['name' => $slug]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

            DB::commit();
            $model->slugPayload = null;
        });
    }

    public function uri(): Attribute
    {
        return new Attribute(
            get: fn () => $this->slug->uri
        );
    }
}
