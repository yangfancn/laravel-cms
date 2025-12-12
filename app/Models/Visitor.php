<?php

namespace App\Models;

use App\Models\Traits\Countable;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'visitable_id', 'visitable_type', 'path', 'os', 'browser', 'user_agent', 'ip', 'country', 'city'];

    public function scopePageViews(Builder $builder, CarbonPeriod $period): void
    {
        $builder
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->select(DB::raw('DATE(created_at) AS visit_date'), DB::raw('COUNT(*) AS page_views'))
            ->groupBy('visit_date');
    }

    public function scopeUniqueIp(Builder $builder, CarbonPeriod $period): void
    {
        $builder
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->select(DB::raw('DATE(created_at) AS visit_date'), DB::raw('COUNT(DISTINCT ip) AS unique_ip_views'))
            ->groupBy('visit_date');
    }

    public function scopeDistribution(Builder $builder, CarbonPeriod $period): void
    {
        $builder
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->select(DB::raw('COUNT(*) AS count'), 'country')
            ->groupBy('country');
    }

    public function scopePopularPage(Builder $builder, CarbonPeriod $period): void
    {
        $builder
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->groupBy('path')  // 按 path 分组
            ->select('path', \DB::raw('COUNT(*) as total_visits'))
            ->orderByDesc('total_visits');
    }

    public function scopeFrequentlyIp(Builder $builder, CarbonPeriod $period): void
    {
        $builder
            ->whereBetween('created_at', [$period->getStartDate(), $period->getEndDate()])
            ->groupBy('ip', 'country')  // 按 path 分组
            ->select('ip', 'country', \DB::raw('COUNT(*) as total_visits'))
            ->orderByDesc('total_visits');
    }

    public static function booted(): void
    {
        static::saving(function (Visitor $visitor) {
            if ($visitor->country === 'Hong Kong') {
                $visitor->country = 'China';
            }
        });

        static::created(function (Visitor $visitor) {

            if (
                $visitor->visitable_type
                && $visitor->visitable_id
                && in_array(Countable::class, class_uses_recursive($visitor->visitable_type))
            ) {
                $view_counts = ViewCount::firstOrCreate([
                    'countable_type' => $visitor->visitable_type,
                    'countable_id' => $visitor->visitable_id,
                ]);

                $view_counts->increment('count');
            }
        });
    }
}
