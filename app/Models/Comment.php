<?php

namespace App\Models;

use App\Models\Traits\Votable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Query\Builder;
use Kalnoy\Nestedset\NodeTrait;
use Kalnoy\Nestedset\QueryBuilder;

class Comment extends Model
{
    use HasFactory, NodeTrait, Votable;

    protected $fillable = ['user_id', 'content', 'images', 'commentable_id', 'commentable_type', 'is_approved'];

    protected $appends = ['approved'];

    public function scopeIsApproved(Builder|QueryBuilder $builder): void
    {
        $builder->where('is_approved', true);
    }

    public function scopeWithUser(Builder|QueryBuilder $builder): void
    {
        $builder->with(['user:id,name,photo']);
    }

    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function approved(): Attribute
    {
        return new Attribute(
            get: function ($value, $attributes) {
                return $attributes['is_approved'] ? 'approved' : 'disapproved';
            }
        );
    }

    public function approve(): self
    {
        $this->is_approved = true;
        $this->save();

        return $this;
    }

    public function disapprove(): self
    {
        $this->is_approved = false;
        $this->save();

        return $this;
    }

    public static function booted(): void
    {
        static::saving(function (self $comment) {
            if (! str_starts_with($comment->commentable_type, 'App\\Models\\')) {
                $comment->commentable_type = 'App\\Models\\'.ucfirst($comment->commentable_type);
            }
        });
    }
}
