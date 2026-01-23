<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Slug extends Model
{
    protected $fillable = ['name'];

    public function uri(): Attribute
    {
        return Attribute::make(
            get: fn () => route('slug', $this)
        );
    }

    public function sluggable(): MorphTo
    {
        return $this->morphTo();
    }
}
