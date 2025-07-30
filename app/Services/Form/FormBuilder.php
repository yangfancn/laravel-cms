<?php

namespace App\Services\Form;

use Illuminate\Database\Eloquent\Model;
use Inertia\Response;

abstract class FormBuilder
{
    abstract public static function render(
        string $action,
        ?string $title = null,
        string $method = 'POST',
        null|Model|array $data = null
    ): Response;
}
