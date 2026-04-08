<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'vote'])]
class Vote extends Model
{
    protected $casts = [
        'vote' => 'boolean',
    ];
}
