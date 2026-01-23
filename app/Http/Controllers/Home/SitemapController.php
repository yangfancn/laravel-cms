<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];

        $posts = Category::where('show', 1)
            ->get()
            ->each(function (Category $category) use (&$urls) {
                $urls[] = [
                    'url' => $category->uri,
                    'lastMod' => today()->format('Y-m-d'),
                ];
            });

        $posts = Post::orderByDesc('id')
            ->limit(200)
            ->get(['id', 'category_id', 'updated_at', 'slug'])
            ->each(function (Post $post) use (&$urls) {
                $urls[] = [
                    'url' => $post->uri,
                    'lastMod' => $post->updated_at->format('Y-m-d'),
                ];
            });

        User::role('editor')
            ->orderByDesc('id')
            ->limit(50)
            ->get()
            ->each(function (User $user) use (&$urls) {
                $urls[] = [
                    'url' => $user->uri,
                    'lastMod' => $user->updated_at->format('Y-m-d'),
                ];
            });

        Tag::orderByDesc('id')
            ->limit(50)
            ->get()
            ->each(function (Tag $tag) use (&$urls) {
                $urls[] = [
                    'url' => $tag->uri,
                    'lastMod' => $tag->updated_at->format('Y-m-d'),
                ];
            });
        $namespace = '<?xml version="1.0" encoding="UTF-8"?>';

        return response()->view('home::sitemaps.xml', compact('urls', 'namespace'))
            ->header('Content-Type', 'text/xml');
    }
}
