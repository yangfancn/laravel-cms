<?php

namespace App\Models\Traits;

use App\Models\ViewCount;
use Illuminate\Database\Eloquent\Relations\MorphOne;

trait Countable
{
    public function counts(): MorphOne
    {
        return $this->MorphOne(ViewCount::class, 'countable');
    }
}
