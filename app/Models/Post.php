<?php

namespace App\Models;

use App\Models\Traits\Commentable;
use App\Models\Traits\Countable;
use App\Models\Traits\Metable;
use App\Models\Traits\Taggable;
use App\Models\Traits\Votable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Post extends Model
{
    use Commentable, HasFactory, Metable, Taggable, Votable, Countable;

    protected $fillable = ['category_id', 'user_id', 'title', 'summary', 'thumb', 'content', 'original_url'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function viewCount(): MorphOne
    {
        return $this->morphOne(ViewCount::class, 'countable');
    }

    public function uri(): Attribute
    {
        return new Attribute(
            get: fn ($value, $attributes) => route('posts.show', $attributes['slug'])
        );
    }

    protected static function booted(): void
    {
        static::addGlobalScope('slugNotNull', fn (Builder $builder) => $builder->whereNotNull('slug'));

        static::created(function (Post $post) {
            $slug = \Str::slug(transliterator_transliterate('Any-Latin; Latin-ASCII', $post->title));

            if (strlen($slug) > 99) {
                $slug = array_reduce(
                    explode('-', $slug),
                    fn (?string $carry, string $word) => strlen($combine = $carry ? ($carry.'-'.$word) : $word) < 99 ? $combine : $carry
                );
            }
            if (Post::where('slug', $slug)->exists()) {
                $post->slug = $slug.'-'.$post->id;
            } else {
                $post->slug = $slug;
            }

            $post->saveQuietly();
        });
    }
}
