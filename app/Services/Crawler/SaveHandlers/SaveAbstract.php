<?php

namespace App\Services\Crawler\SaveHandlers;

use Illuminate\Database\Eloquent\Model;

abstract class SaveAbstract
{
    abstract public function save(array $data, string $url): bool|Model;
}
