<?php

namespace App\Http\Controllers\Home;

use App\Facades\Seo;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\View\View;
use Spatie\ResponseCache\Attributes\FlexibleCache;

class IndexController extends Controller
{
    #[FlexibleCache(300, 60, ['index'])]
    public function index(): View
    {
        Seo::model();

        $trending = Post::trending(7, 9, ['categories.slug']);

        $latest = Post::query()
            ->with(['slug', 'media'])
            ->latest('created_at')
            ->limit(26)
            ->get(['id', 'title', 'summary', 'created_at']);

        return view('home::index', compact('latest', 'trending'));
    }
}
