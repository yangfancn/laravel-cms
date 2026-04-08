<?php

namespace App\Models;

use Illuminate\Console\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Table(timestamps: false)]
#[Fillable(['title', 'keywords', 'description', 'others'])]
#[Hidden(['metable_type', 'metable_id'])]
class Meta extends Model
{
    use LogsActivity;

    protected $casts = [
        'others' => 'array',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function metable(): MorphTo
    {
        return $this->morphTo();
    }
}
