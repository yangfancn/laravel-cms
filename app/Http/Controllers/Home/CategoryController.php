<?php

namespace App\Http\Controllers\Home;

use App\Facades\Seo;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        $category->meta ? Seo::model($category) : Seo::seo($category->name);

        if (! $category->type) {
            return view("home::categories.{$category->full_path}", compact('category'));
        }

        $posts = $category
            ->posts()
            ->orderByDesc('created_at')
            ->simplePaginate(15);

        return view('home::categories.post-list', compact('category', 'posts'));
    }
}
