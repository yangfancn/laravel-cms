<?php

namespace App\Http\Renderers;

use App\Enums\CategoryType;
use App\Http\Renderers\Contracts\CategorySubRenderer;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Response;

class CategoryPostsRenderer implements CategorySubRenderer
{
    public function supports(Category $category): bool
    {
        return $category->type === CategoryType::Posts->value;
    }

    public function render(Category $category): Response
    {
        $posts = Post::query()
            ->whereHas(
                'categories',
                fn ($builder) => $builder->whereIn(
                    'categories.id',
                    $category->descendantsAndSelf($category->id, ['id'])->pluck('id')
                )
            )
            ->latest()
            ->with(['slug', 'media'])
            ->select(['posts.id', 'title', 'summary', 'posts.created_at'])
            ->simplePaginate(15);

        $trending = Post::trending(3, 7, category: $category);

        return response()
            ->view(
                'home::categories.post-list',
                compact('posts', 'trending', 'category')
            );
    }
}
