<?php

namespace App\Http\Renderers;

use App\Facades\Seo;
use App\Http\Renderers\Contracts\SlugRenderer;
use App\Http\Renderers\Exceptions\RendererNotFoundException;
use App\Models\Category;
use \Illuminate\Http\Response;
use App\Http\Renderers\Contracts\CategorySubRenderer;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryRenderer implements SlugRenderer
{
    /** @param iterable<CategorySubRenderer> $renderers */
    public function __construct(private iterable $renderers)
    {
    }
    public function supprots(object $target): bool
    {
        return $target instanceof Category;
    }

    /**
     * @param Category $target
     * @return void
     */
    public function renderer(object $target): Response
    {
        Seo::activeCategory($target);
        $target->meta ? Seo::model($target) : Seo::seo($target->name);

        foreach ($this->renderers as $renderer) {
            if ($renderer->supports($target)) {
                return $renderer->render($target);
            }
        }

        throw new RendererNotFoundException();
    }
}
