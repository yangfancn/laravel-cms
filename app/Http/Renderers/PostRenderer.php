<?php

namespace App\Http\Renderers;

use App\Facades\Seo;
use App\Http\Renderers\Contracts\SlugRenderer;
use App\Models\Post;
use Illuminate\Http\Response;

class PostRenderer implements SlugRenderer
{
    public function supprots(object $target): bool
    {
        return $target instanceof Post;
    }

    /**
     * @param  Post  $target
     * @return void
     */
    public function renderer(object $post): Response
    {
        $post->meta()->exists()
            ? Seo::model($post)
            : Seo::seo($post->title, description: $post->summary ?: null);

        $related = $post->category_id
            ? Post::query()
                ->whereKeyNot($post->id)
                ->latest('created_at')
                ->limit(3)
                ->get(['id', 'title', 'slug', 'thumb', 'summary', 'created_at'])
            : collect();

        return response()->view(
            'home::posts.show',
            compact('post', 'related')
        );
    }
}
