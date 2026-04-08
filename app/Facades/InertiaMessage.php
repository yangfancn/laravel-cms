<?php

namespace App\Facades;

use App\Services\InertiaMessage\Enums\Position;
use Illuminate\Support\Facades\Facade;

/**
 * @method static void success(string $message, ?string $caption = null, ?Position $position = Position::TOP)
 * @method static void error(string $message, ?string $caption = null, ?Position $position = Position::TOP)
 * @method static void warning(string $message, ?string $caption = null, ?Position $position = Position::TOP)
 * @method static void info(string $message, ?string $caption = null, ?Position $position = Position::TOP)
 */
class InertiaMessage extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \App\Services\InertiaMessage\InertiaMessage::class;
    }
}
