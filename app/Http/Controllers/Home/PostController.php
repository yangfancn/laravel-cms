<?php

namespace App\Http\Controllers\Home;

use App\Facades\Seo;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request, Post $post): View
    {
        Seo::seo('Search Result')->noindex();
        $posts = $post->whereLike('title', "%{$request->string('search')}%")
            ->orderByDesc('created_at')
            ->simplePaginate(9)
            ->withQueryString();

        return view('home::categories.post-list', compact('posts'));
    }

    public function show(Post $post): View
    {
        $post->meta ? Seo::model($post) : Seo::seo($post->title);

        return view('home::posts.show', compact('post'));
    }
}
