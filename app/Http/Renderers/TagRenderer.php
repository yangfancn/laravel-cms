<?php

namespace App\Http\Renderers;

use App\Facades\Seo;
use App\Http\Renderers\Contracts\SlugRenderer;
use App\Models\Tag;
use Illuminate\Http\Response;

class TagRenderer implements SlugRenderer
{


    public function supprots(object $target): bool
    {
        return $target instanceof Tag;
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function renderer(object $tag): Response
    {
        $description = "Latest reporting and analysis tagged with {$tag->name}.";

        Seo::seo($tag->name, description: $description);

        $posts = $tag->posts()
            ->orderByDesc('created_at')
            ->with(['slug', 'media'])
            ->select(['id', 'title', 'summary', 'created_at'])
            ->simplePaginate(9);

        return response()
            ->view(
                'home::tags.show',
                compact('tag', 'posts', 'description')
            );
    }
}
