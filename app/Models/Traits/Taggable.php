<?php

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
    protected ?array $tagsPayload = null;

    public function initializeTaggable(): void
    {
        if (! empty($this->fillable)) {
            $this->mergeFillable(['tags']);
        }
    }

    public function setTagsAttribute(?array $tags): void
    {
        $this->tagsPayload = $tags ? array_map(fn (int|string $item) => is_int($item) ? $item : Tag::firstOrCreate(['name' => $item]), $tags) : $tags;
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    /**
     * sync tags by tagName
     *
     * @param  string[]  $tags
     */
    public function syncTagsByName(array $tags): static
    {
        $this
            ->tags()
            ->sync(collect(Tag::findOrCreate($tags))->pluck('id')->all());

        return $this;
    }

    protected static function bootTaggable(): void
    {
        static::deleted(function (self $model) {
            $model->tags()->detach();
        });

        static::saved(function (self $model) {
            if ($model->tagsPayload) {
                $model->tags()->sync($model->tagsPayload);
                $model->tagsPayload = null;
            }
        });
    }
}
