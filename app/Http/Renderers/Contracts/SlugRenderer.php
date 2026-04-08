<?php

namespace App\Http\Renderers\Contracts;

use Illuminate\Http\Response;

interface SlugRenderer
{
    public function supprots(object $target): bool;

    public function renderer(object $target): Response;
}
