<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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
