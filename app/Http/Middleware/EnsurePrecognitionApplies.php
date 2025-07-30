<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePrecognitionApplies
{
    /**
     * 确保只有 POST/PUT 请求使用 HandlePrecognitiveRequests 中间件（package: Laravel Precognition）
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['POST', 'PUT'])) {
            return app(HandlePrecognitiveRequests::class)->handle($request, $next);

        }

        return $next($request);
    }
}
