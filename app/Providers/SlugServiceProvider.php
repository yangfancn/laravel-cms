<?php

namespace App\Providers;

use App\Http\Renderers\CategoryPostsRenderer;
use App\Http\Renderers\CategoryRenderer;
use App\Http\Renderers\CategoryViewRenderer;
use App\Http\Renderers\Contracts\CategorySubRenderer;
use App\Http\Renderers\Contracts\SlugRenderer;
use App\Http\Renderers\PostRenderer;
use App\Http\Renderers\SlugRenderManager;
use App\Http\Renderers\TagRenderer;
use Illuminate\Support\ServiceProvider;

class SlugServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->tag([
            CategoryPostsRenderer::class,
            CategoryViewRenderer::class,
        ], CategorySubRenderer::class);

        $this->app->singleton(CategoryRenderer::class, fn ($app) => new CategoryRenderer(
            $app->tagged(CategorySubRenderer::class)
        ));

        $this->app->tag([
            CategoryRenderer::class,
            PostRenderer::class,
            TagRenderer::class,
        ], SlugRenderer::class);

        $this->app->singleton(SlugRenderManager::class, fn ($app) => new SlugRenderManager(
            $app->tagged(SlugRenderer::class)
        ));
    }
}
