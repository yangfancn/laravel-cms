<?php

namespace App\Services\InertiaMessage;

use App\Services\InertiaMessage\Enums\Position;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

/**
 * InertiaMessage class for managing Inertia notifications.
 * @package App\Services\InertiaMessage
 */
class InertiaMessage
{
    private string $cache_key;

    public function __construct()
    {
        $this->cache_key = 'inertiaNotify_'.Session::id();
    }

    public function success(string $message, ?string $caption = null, ?Position $position = Position::TOP): void
    {
        $this->pushMessage('positive', $message, $caption, $position);
    }

    public function error(string $message, ?string $caption = null, ?Position $position = Position::TOP): void
    {
        $this->pushMessage('negative', $message, $caption, $position);
    }

    public function warning(string $message, ?string $caption = null, ?Position $position = Position::TOP): void
    {
        $this->pushMessage('warning', $message, $caption, $position);
    }

    public function info(string $message, ?string $caption = null, ?Position $position = Position::TOP): void
    {
        $this->pushMessage('info', $message, $caption, $position);
    }

    public function getMessages(): array
    {
        /**
         * @var array $messages
         */
        $messages = Cache::get($this->cache_key, []);
        $this->flushMessages();

        return $messages;
    }

    private function flushMessages(): void
    {
        Cache::forget($this->cache_key);
    }

    private function pushMessage(string $type, string $message, ?string $caption, Position $position): void
    {
        $messages = array_merge($this->getMessages(), [compact('type', 'message', 'caption', 'position')]);
        Cache::put($this->cache_key, $messages, now()->addMinutes(2));
    }
}
