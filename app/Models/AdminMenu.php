<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @mixin IdeHelperAdminMenu
 */
class AdminMenu extends Model
{
    use HasFactory, NodeTrait;

    protected $fillable = ['label', 'route', 'route_params', 'icon', 'icon_color'];

    public $timestamps = null;

    protected $casts = [
        'route_params' => 'array',
    ];

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(AdminMenu::class, 'parent_id');
    }

    public function params(): Attribute
    {
        return Attribute::make(
            get: fn ($val, array $attributes) => collect(json_decode($attributes['route_params']))->pluck('value', 'name')->all()
        );
    }
}
