<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $page = $user->page;

        if (!$page) {
            return redirect()->route('pages.create')
                ->with('info', 'Please create your page first.');
        }

        // Get analytics data
        $dailyStats = $this->analyticsService->getDailyStats($page, 30);

        $stats = [
            'total_views' => $page->getTotalViews(),
            'unique_views' => $page->getUniqueViews(),
            'daily_views' => $page->getDailyViews(),
            'weekly_views' => $page->getWeeklyViews(),
            'monthly_views' => $page->getMonthlyViews(),
        ];

        return view('dashboard', compact('page', 'stats', 'dailyStats'));
    }
}
