<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperBot
 */
class Bot extends Model
{
    protected $fillable = ['baidu', 'bing', 'duckduckgo', 'google', 'yandex', 'other'];
}
