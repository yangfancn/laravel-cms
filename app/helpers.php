<?php

use Illuminate\Support\Collection;

function flattenTree(Collection $collection, string $prefix = '', string $column = 'name'): Collection
{
    // use a fresh collection each call to avoid cross-call state leakage
    $flatten = collect();

    foreach ($collection as $category) {
        $isLast = $category === $collection->last();

        if (! $category->parent_id) {
            $category->flatten = $category->name;
            // $prefix = '<i class="tree space-half"></i>';
        } else {
            $category->flatten = $prefix
                . ($isLast ? '<i class="tree end"></i>' : '<i class="tree branch"></i>')
                . $category->$column;
        }

        $flatten->push($category);

        if ($category->children->isNotEmpty()) {
            $children = flattenTree(
                $category->children,
                $prefix . ($isLast ? '<i class="tree space"></i>' : '<i class="tree line"></i>'),
                $column
            );

            $flatten = $flatten->merge($children);
        }
    }

    return $flatten;
}

function make_slug(string $str, int $maxLength = 99): string
{
    $slug = \Str::slug(transliterator_transliterate('Any-Latin; Latin-ASCII', $str));

    if (strlen($slug) > $maxLength) {
        $slug = array_reduce(
            explode('-', $slug),
            fn (?string $carry, string $word) => strlen($combine = $carry ? ($carry.'-'.$word) : $word) < $maxLength ?
                $combine : $carry
        );
    }

    return $slug;
}

function getModelName(string $commentableType): string
{
    return 'App\\Models\\'.ucfirst($commentableType);
}
