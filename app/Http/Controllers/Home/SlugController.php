<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Renderers\SlugRenderManager;
use App\Models\Slug;
use Illuminate\Http\Response;

class SlugController extends Controller
{
    public function __construct(
        private readonly SlugRenderManager $renderer
    ) {}

    public function __invoke(Slug $slug): Response
    {
        return $this->renderer->render($slug);
    }
}
