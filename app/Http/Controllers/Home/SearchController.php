<?php

namespace App\Http\Controllers\Home;

use App\Facades\Seo;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $type = $request->string('type', 'broker')->value();
        if (! $q = $request->string('q')->value()) {
            abort(400);
        }

        Seo::seo("Search Result For $q")->noindex();

        $posts = Post::with(['slug', 'media' => fn ($q) => $q->where('collection_name', 'thumb')])
            ->where('title', 'like', "%$q%")
            ->latest()
            ->select(['posts.id', 'title', 'summary', 'posts.created_at'])
            ->simplePaginate(15)
            ->withQueryString();

        return view('home::search', compact('posts', 'q', 'type'));
    }
}
