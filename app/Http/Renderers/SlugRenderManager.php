<?php

namespace App\Http\Renderers;

use App\Facades\Seo;
use App\Http\Renderers\Exceptions\RendererNotFoundException;
use App\Models\Slug;
use App\Http\Renderers\Contracts\SlugRenderer;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SlugRenderManager
{
    /**
     * @var SlugRenderer[]
     */
    private array $renderers;

    /**
     * @param iterable<SlugRenderer> $renderers
     */
    public function __construct(iterable $renderers)
    {
        $this->renderers = \is_array($renderers)
            ? $renderers
            : iterator_to_array($renderers);
    }

    public function render(Slug $slug): Response
    {
        if (! $target = $slug->sluggable) {
            throw new NotFoundHttpException();
        }

        $target->setRelation('slug', $slug->unsetRelations());

        foreach ($this->renderers as $renderer) {
            if ($renderer->supprots($target)) {
                return $renderer->renderer($target);
            }
        }
        throw new RendererNotFoundException();
    }
}
