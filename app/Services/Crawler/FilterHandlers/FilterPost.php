<?php

namespace App\Services\Crawler\FilterHandlers;

use Illuminate\Support\Facades\DB;

class FilterPost extends FilterAbstract
{
    public function filter(array $data): bool
    {
        return ! $data['link']
            || DB::table('posts')->where('original_url', $data['link'])->exists();
    }
}
