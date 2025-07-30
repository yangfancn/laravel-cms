<?php

namespace App\Http\Controllers\Home;

use App\Facades\Seo;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;

class IndexController extends Controller
{
    public function index(): View
    {
        Seo::model();

        $posts = Post::orderBy('created_at', 'desc')->limit(20)->get();

        return view('home::index', compact('posts'));
    }
}
