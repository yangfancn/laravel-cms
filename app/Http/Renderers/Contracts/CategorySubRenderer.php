<?php

namespace App\Http\Renderers\Contracts;

use App\Models\Category;
use Illuminate\Http\Response;

interface CategorySubRenderer
{
    public function supports(Category $category): bool;
    public function render(Category $category): Response;
}
