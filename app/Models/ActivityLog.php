<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Activity;

class ActivityLog extends Activity
{
    protected $appends = ['data'];

    public function data(): Attribute
    {
        return Attribute::make(
            get: fn ($_, $attributes) => $attributes['properties']
        );
    }
}
