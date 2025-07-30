<?php

namespace App\Models;

use App\Models\Traits\Metable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    use HasFactory, Metable, NodeTrait;

    protected $fillable = ['name', 'directory', 'full_path', 'parent_id', 'route', 'show', 'type', 'rank'];

    public function flatName(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                if (! $this->relationLoaded('parent') || ! $this->parent) {
                    return $attributes['name'];
                }

                $isLast = $this->getRgt() === $this->parent?->getRgt() - 1;
                $indent = '';

                // 计算缩进
                $ancestors = $this->ancestors()->get(); // 获取当前节点的所有祖先
                foreach ($ancestors as $ancestor) {
                    $isAncestorLast = $ancestor->getRgt() === $ancestor->parent?->getRgt() - 1;
                    $indent .= $isAncestorLast ? str_repeat('&nbsp;', 8) : '│'.str_repeat('&nbsp;', 6);
                }

                // 添加缩进并处理当前节点
                $indent = str_repeat('&nbsp;', 2).$indent;

                return $indent.($isLast ? '└── ' : '├── ').$attributes['name'];
            }

        );
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function uri(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                if ($attributes['route']) {
                    return route($attributes['route'], ['search' => request()->get('search')]);
                }
                return route('category', [
                    'category' => $attributes['full_path'],
                    'search' => request()->get('search'),
                ]);
            }
        );
    }

    public static function booted(): void
    {
        static::saving(function (Category $category) {
            if ($category->isDirty(['directory', 'parent_id'])) {
                $category->full_path = $category->ancestors()->get()->reduce(fn ($a, Category $category) => $a.$category->directory.'/').$category->directory;
            }
        });
    }
}
