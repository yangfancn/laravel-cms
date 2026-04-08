<?php

namespace App\Http\Middleware\Home;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ViteBuildDir
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        \Vite::useBuildDirectory('build/home');

        return $next($request);
    }
}
