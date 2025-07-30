<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTag
 */
class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public static function booted(): void
    {
        static::saving(function (Tag $tag) {
            if (! $tag->slug) {
                $tag->slug = make_slug($tag->name);
            }
        });
    }
}
