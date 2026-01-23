<?php

namespace App\Services;

use Auth;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;
use Symfony\Component\HttpFoundation\Response;

class ShouldCacheProfile implements CacheProfile
{
    public function enabled(Request $request): bool
    {
        return config('responsecache.enabled');
    }

    public function cacheRequestUntil(Request $request): DateTime
    {
        return Carbon::now()->addSeconds(
            config('responsecache.cache_lifetime_in_seconds')
        );
    }

    public function useCacheNameSuffix(Request $request): string
    {
        return Auth::check()
            ? (string) Auth::id()
            : '';
    }

    public function isRunningInConsole(): bool
    {
        if (app()->environment('testing')) {
            return false;
        }

        return app()->runningInConsole();
    }

    /**
     * 是否应该缓存这个请求
     */
    public function shouldCacheRequest(Request $request): bool
    {
        // 未登录才缓存（双保险：enabled 已判断一次，这里再判断一次避免被单独调用时漏掉）
        if (auth()->check()) {
            return false;
        }

        // 不缓存 ajax 请求
        if ($request->ajax()) {
            return false;
        }

        // 只缓存 GET
        return $request->isMethod('get');
    }

    /**
     * 是否应该缓存这个响应
     */
    public function shouldCacheResponse(Response $response): bool
    {
        if (! $this->hasCacheableResponseCode($response)) {
            return false;
        }

        if (! $this->hasCacheableContentType($response)) {
            return false;
        }

        return true;
    }

    private function hasCacheableResponseCode(Response $response): bool
    {
        return $response->isSuccessful() || $response->isRedirection();
    }

    private function hasCacheableContentType(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');

        if (str_starts_with($contentType, 'text/')) {
            return true;
        }

        if (Str::contains($contentType, ['/json', '+json'])) {
            return true;
        }

        return false;
    }
}
