<?php

namespace App\Models\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Taggable
{
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
            if ($tag_ids = request('tags')) {
                $model->tags()->sync($tag_ids);
            }
        });
    }
}
