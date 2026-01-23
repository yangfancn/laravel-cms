<?php

namespace App\Models;

use App\Models\Traits\Categorizable;
use App\Models\Traits\Commentable;
use App\Models\Traits\Countable;
use App\Models\Traits\HtmlImagesToMedia;
use App\Models\Traits\Metable;
use App\Models\Traits\Sluggable;
use App\Models\Traits\SyncMedia;
use App\Models\Traits\Taggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Query\JoinClause;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Post extends Model implements HasMedia
{
    use Categorizable;
    use Commentable;
    use Countable;
    use HtmlImagesToMedia;
    use InteractsWithMedia;
    use LogsActivity;
    use Metable;
    use Sluggable;
    use SyncMedia;
    use Taggable;

    protected $fillable = ['user_id', 'title', 'summary', 'content', 'original_url'];

    public array $slugBy = ['title'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('thumb')
            ->singleFile();
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('small')
            ->performOnCollections('thumb')
            ->fit(Fit::Contain, 300, 225)
            ->quality(80)
            ->format('jpg')
            ->nonQueued();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function viewCount(): MorphOne
    {
        return $this->morphOne(ViewCount::class, 'countable');
    }

    public function visitors(): MorphMany
    {
        return $this->morphMany(Visitor::class, 'visitable');
    }

    public static function trending(
        int $subDays,
        int $limit,
        array $loadRelations = [],
        ?Category $category = null,
        ?User $user = null
    ): Collection {
        $since = now()->subDays($subDays);

        // 1) 在 visitors 里先做聚合：只统计近 N 天、且 visitable_type=Post
        $visitorsAgg = Visitor::query()
            ->from('visitors as v')
            ->selectRaw('v.visitable_id, COUNT(*) as visitors_count')
            ->where('v.visitable_type', self::class)
            ->where('v.created_at', '>=', $since)
            ->groupBy('v.visitable_id')
            ->when(
                $category, fn (Builder $builder) => $builder
                    ->join(
                        'categorizables as c',
                        fn (JoinClause $join) => $join
                            ->on('c.categorizable_id', '=', 'v.visitable_id')
                            ->where('c.categorizable_type', self::class)
                            ->where('c.category_id', $category->id)
                    )
            );

        // 2) 主查询：join 聚合结果，再取 posts 字段，按 visitors_count 排序取 Top N
        $query = self::query()
            ->with([...$loadRelations, 'slug'])
            ->select([
                'posts.id',
                'posts.title',
                'posts.created_at',
                't.visitors_count',
            ])
            ->joinSub($visitorsAgg, 't', fn ($join) => $join->on('t.visitable_id', '=', 'posts.id'))
            ->when($user, fn ($q) => $q->where('posts.user_id', $user->id))
            ->orderByDesc('t.visitors_count')
            ->limit($limit);

        return $query->get();
    }
}
