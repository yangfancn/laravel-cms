<?php

namespace App\Services;

use Illuminate\Support\Collection;

/**
 * Menu class for managing hierarchical channel structures.
 */
class Menu
{
    public function __construct(protected Collection $channels) {}

    public function getChannels(int $parent_id = 0, bool $list = false): Collection
    {
        $tree = $this->channels->where('parent_id', $parent_id)->map(function ($item) {
            $item['children'] = $this->getChannels($item['id'])->values();

            return $item;
        });

        return $list ? $this->flatten($tree) : $tree;
    }

    public function flatten(
        Collection $tree,
        int $space = 6,
        string $levelStr = '├─',
        string $endLevelStr = '└─',
        int $depth = 1
    ): Collection {
        $flatten = collect();

        $tree->values()->each(function ($item, $index) use (&$flatten, $space, $levelStr, $endLevelStr, $depth, $tree) {
            $item->level_prefix = $item->parent_id ? str_repeat('&nbsp;', $space * ($depth - 1))
                .($tree->count() - 1 === $index ? $endLevelStr : $levelStr) : '';
            $flatten->push($item);
            if ($item->children) {
                $flatten = $flatten->merge($this->flatten($item->children, $space, $levelStr, $endLevelStr, ++$depth));
            }
        });

        return $flatten;
    }

    public static function toNestedArray(Collection $tree): array
    {
        return $tree->map(function ($item) {
            $item['children'] = self::toNestedArray($item['children']);

            return $item;
        })->toArray();
    }
}
