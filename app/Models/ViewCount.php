<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViewCount extends Model
{
    use HasFactory;

    protected $fillable = ['count', 'countable_id', 'countable_type'];

    public $timestamps = false;
}
