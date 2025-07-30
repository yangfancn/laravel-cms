<?php

namespace App\Providers;

use App\Services\InertiaMessage\InertiaMessage;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class InertiaMessageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(InertiaMessage::class, fn(Application $app) => new InertiaMessage);
    }
}
