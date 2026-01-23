<?php

namespace App\Models;

use App\Models\Traits\Metable;
use App\Models\Traits\Sluggable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Category extends Model
{
    use HasFactory;
    use LogsActivity;
    use Metable;
    use NodeTrait;
    use Sluggable;

    protected $fillable = ['name', 'directory', 'parent_id', 'show', 'type', 'rank'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

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

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'categorizable')->withTimestamps();
    }

    public static function booted(): void
    {
        static::saving(function (Category $category) {
            if ($category->isDirty(['directory', 'parent_id'])) {
                $category->slug = $category->ancestors()->get()->reduce(fn ($a, Category $category) => $a.$category->directory.'/').$category->directory;
            }
        });
    }
}
