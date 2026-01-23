<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Renderers\SlugRenderManager;
use App\Models\Slug;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SlugController extends Controller
{
    public function __construct(
        private readonly SlugRenderManager $renderer
    ) {
    }

    public function __invoke(Slug $slug): Response
    {
        return $this->renderer->render($slug);
    }
}
