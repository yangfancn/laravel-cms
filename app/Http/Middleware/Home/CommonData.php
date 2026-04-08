<?php

namespace App\Http\Middleware\Home;

use App\Models\Category;
use App\Models\Site;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class CommonData
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Shared Data
        View::share('site', Site::first());
        View::share('nav', Category::with('slug')->get()->toTree());

        return $next($request);
    }
}
