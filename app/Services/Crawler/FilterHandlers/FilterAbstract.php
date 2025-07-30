<?php

namespace App\Services\Crawler\FilterHandlers;

abstract class FilterAbstract
{
    abstract public function filter(array $data): bool;
}
