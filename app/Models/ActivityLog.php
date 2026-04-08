<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Appends;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Activitylog\Models\Activity;

#[Appends(['data'])]
class ActivityLog extends Activity
{
    public function data(): Attribute
    {
        return Attribute::make(
            get: fn ($_, $attributes) => $attributes['properties']
        );
    }
}
