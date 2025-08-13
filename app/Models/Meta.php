<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends Model
{
    protected $fillable = ['title', 'keywords', 'description', 'others'];

    protected $hidden = ['metable_type', 'metable_id'];

    public $timestamps = false;

    protected $casts = [
        'others' => 'array',
    ];

    public function metable(): MorphTo
    {
        return $this->morphTo();
    }
}
