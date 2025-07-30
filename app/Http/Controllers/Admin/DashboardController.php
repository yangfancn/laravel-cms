<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bot;
use App\Models\Post;
use App\Models\User;
use App\Models\Visitor;
use Carbon\CarbonPeriod;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        $now = now();

        $period = CarbonPeriod::create(now()->subDays(30), $now);

        $bots = Bot::whereBetween('created_at', $period)->get();

        $pageVisits = Visitor::pageViews($period)->get();

        $uniqueVisitors = Visitor::uniqueIp($period)->get();

        $visitDistribution = Visitor::distribution(CarbonPeriod::create(now()->subDays(7), now()))->get();

        $popularPages = Visitor::popularPage(CarbonPeriod::create(now()->subDays(3), now()))->limit(5)->get();

        $frequentlyIps = Visitor::frequentlyIp(CarbonPeriod::create(now()->subDays(3), now()))->limit(5)->get();

        $newPosts = Post::whereDate('created_at', now())->count();
        $yesterdayPosts = Post::whereDate('created_at', now()->subDay())->count();
        $newPostsChangePercent = $yesterdayPosts ? (($newPosts - $yesterdayPosts) * 100 / $yesterdayPosts) : null;

        $newUsers = User::whereDate('created_at', now())->count();
        $yesterdayUsers = User::whereDate('created_at', now()->subDay())->count();
        $newUsersChangePercent = $yesterdayUsers ? (($newUsers - $yesterdayUsers) * 100 / $yesterdayUsers) : null;

        return inertia('Dashboard/Index', compact(
            'bots',
            'pageVisits',
            'uniqueVisitors',
            'visitDistribution',
            'popularPages',
            'frequentlyIps',
            'newPosts',
            'newPostsChangePercent',
            'newUsers',
            'newUsersChangePercent'
        ));
    }
}
