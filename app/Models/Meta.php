<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Meta extends Model
{
    use LogsActivity;

    protected $fillable = ['title', 'keywords', 'description', 'others'];

    protected $hidden = ['metable_type', 'metable_id'];

    public $timestamps = false;

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
