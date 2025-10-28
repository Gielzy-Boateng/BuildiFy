<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsService
{
    public function trackPageView(Page $page, $request)
    {
        $sessionId = $request->session()->getId();
        $ipAddress = $request->ip();

        // Check if this is a unique visitor (first visit in 30 days)
        $existingView = PageView::where('page_id', $page->id)
            ->where('ip_address', $ipAddress)
            ->where('visited_at', '>=', now()->subDays(30))
            ->first();

        PageView::create([
            'page_id' => $page->id,
            'ip_address' => $ipAddress,
            'user_agent' => $request->userAgent(),
            'session_id' => $sessionId,
            'is_unique_visitor' => !$existingView,
            'visited_at' => now(),
        ]);
    }

    public function getDailyStats(Page $page, $days = 30)
    {
        return PageView::where('page_id', $page->id)
            ->where('visited_at', '>=', now()->subDays($days))
            ->select(
                DB::raw('DATE(visited_at) as date'),
                DB::raw('COUNT(*) as total_views'),
                DB::raw('SUM(is_unique_visitor) as unique_views')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
    }

    public function getWeeklyStats(Page $page, $weeks = 12)
    {
        return PageView::where('page_id', $page->id)
            ->where('visited_at', '>=', now()->subWeeks($weeks))
            ->select(
                DB::raw('YEARWEEK(visited_at) as week'),
                DB::raw('COUNT(*) as total_views'),
                DB::raw('SUM(is_unique_visitor) as unique_views')
            )
            ->groupBy('week')
            ->orderBy('week', 'asc')
            ->get();
    }

    public function getMonthlyStats(Page $page, $months = 12)
    {
        return PageView::where('page_id', $page->id)
            ->where('visited_at', '>=', now()->subMonths($months))
            ->select(
                DB::raw('YEAR(visited_at) as year'),
                DB::raw('MONTH(visited_at) as month'),
                DB::raw('COUNT(*) as total_views'),
                DB::raw('SUM(is_unique_visitor) as unique_views')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();
    }

    public function getTopPages($limit = 10)
    {
        return Page::withCount('pageViews')
            ->orderBy('page_views_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function exportAnalytics(Page $page)
    {
        return PageView::where('page_id', $page->id)
            ->select('visited_at', 'ip_address', 'user_agent', 'is_unique_visitor')
            ->orderBy('visited_at', 'desc')
            ->get();
    }
}
