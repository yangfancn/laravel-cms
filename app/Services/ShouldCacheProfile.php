<?php

namespace App\Services;

use Illuminate\Http\Request;
use Spatie\ResponseCache\CacheProfiles\CacheProfile;
use Symfony\Component\HttpFoundation\Response;

class ShouldCacheProfile implements CacheProfile
{
    public function enabled(Request $request): bool
    {
        return config('responsecache.enabled');
    }

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

        // Only cache GET and HEAD requests
        return $request->isMethod('GET') || $request->isMethod('HEAD');
    }

    public function shouldCacheResponse(Response $response): bool
    {
        return $response->isSuccessful();
    }

    public function cacheLifetimeInSeconds(Request $request): int
    {
        return 30 * 60;
    }

    public function useCacheNameSuffix(Request $request): string
    {
        // Return a unique string per user (or empty for shared cache)
        return auth()->user()?->id ?? '';
    }
}
