<?php

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
    protected ?array $tagsPayload = null;

    public function initializeTaggable(): void
    {
        $this->mergeFillable(['tags']);
    }

    public function setTagsAttribute(?array $tags): void
    {
        $this->tagsPayload = $tags;
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
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
