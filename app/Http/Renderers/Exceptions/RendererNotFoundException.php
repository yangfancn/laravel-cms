<?php

namespace App\Http\Renderers\Exceptions;

use RuntimeException;

class RendererNotFoundException extends RuntimeException
{
    public function __construct()
    {
        $message = 'No renderer defined for this request . Please register a renderer in SlugServiceProvider.';

        parent::__construct($message, 500);
    }
}
