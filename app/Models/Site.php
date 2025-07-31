<?php

namespace App\Models;

use App\Models\Traits\Metable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory, Metable;

    protected $fillable = ['name'];
}
