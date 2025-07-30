<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperVote
 */
class Vote extends Model
{
    protected $fillable = ['user_id', 'vote'];

    protected $casts = [
        'vote' => 'boolean',
    ];
}
