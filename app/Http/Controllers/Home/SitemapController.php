<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Response;
use Spatie\ResponseCache\Attributes\FlexibleCache;

class SitemapController extends Controller
{
    #[FlexibleCache(1200, 120, ['slug'])]
    public function __invoke(): Response
    {
        $urls = [];

        Category::with('slug')
            ->where('show', 1)
            ->get()
            ->each(function (Category $category) use (&$urls) {
                $urls[] = [
                    'url' => $category->uri,
                    'lastMod' => today()->format('Y-m-d'),
                ];
            });

        Post::with('slug')
            ->orderByDesc('id')
            ->limit(200)
            ->get(['id', 'updated_at'])
            ->each(function (Post $post) use (&$urls) {
                $urls[] = [
                    'url' => $post->uri,
                    'lastMod' => $post->updated_at->format('Y-m-d'),
                ];
            });

        $namespace = '<?xml version="1.0" encoding="UTF-8"?>';

        return response()->view('home::sitemaps.xml', compact('urls', 'namespace'))
            ->header('Content-Type', 'text/xml');
    }
}
