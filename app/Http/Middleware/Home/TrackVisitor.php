<?php

namespace App\Http\Middleware\Home;

use App\Events\VisitorLogged;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    public function handle(Request $request, Closure $next): Response
    {
        event(new VisitorLogged($request));

        return $next($request);
    }
}
