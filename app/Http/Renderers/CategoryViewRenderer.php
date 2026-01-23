<?php

namespace App\Http\Renderers;

use App\Enums\CategoryType;
use App\Http\Renderers\Contracts\CategorySubRenderer;
use App\Models\Category;
use Illuminate\Http\Response;

class CategoryViewRenderer implements CategorySubRenderer
{
    public function supports(Category $category): bool
    {
        return $category->type === CategoryType::View->value;
    }

    public function render(Category $category): Response
    {
        $view = "home::categories.{$category->slug->name}";

        return response()
            ->view($view, compact('category'));
    }
}
