<?php

namespace App\Listeners;

use App\Events\VisitorLogged;
use App\Jobs\ProcessVisitorLog;
use Illuminate\Database\Eloquent\Model;

class LogVisitor
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(VisitorLogged $event): void
    {
        $params = $event->request->route()->parameters() ?: [];
        $model = array_pop($params);

        if (! $model instanceof Model) {
            $model = null;
        }

        ProcessVisitorLog::dispatch(
            $event->request->user()?->id,
            $event->request->path(),
            $event->request->userAgent() ?: '',
            $event->request->ip(),
            $event->request->server,
            $model ? get_class($model) : null,
            $model ? $model->getKey() : null,
        );
    }
}
