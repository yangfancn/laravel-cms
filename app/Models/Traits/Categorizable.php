<?php

namespace App\Models\Traits;

use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait Categorizable
{
    protected ?array $categoriesPayload = null;

    public function initializeCategorizable(): void
    {
        $this->mergeFillable(['categories']);
    }

    public function setCategoriesAttribute(?array $categories): void
    {
        $this->categoriesPayload = array_map(fn (int|string $item) => is_int($item) ? $item : Category::firstOrCreate(['name' => $item]), $categories);
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    protected static function bootCategorizable(): void
    {
        static::deleted(function (self $model) {
            $model->categories()->detach();
        });

        static::saved(function (self $model) {
            if ($model->categoriesPayload) {
                $model->categories()->sync($model->categoriesPayload);
                $model->categoriesPayload = null;
            }
        });
    }
}
