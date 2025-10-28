<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnalyticsController extends Controller
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
            return redirect()->route('dashboard')
                ->with('error', 'No page found.');
        }

        $period = $request->get('period', 'daily');

        $stats = match ($period) {
            'weekly' => $this->analyticsService->getWeeklyStats($page),
            'monthly' => $this->analyticsService->getMonthlyStats($page),
            default => $this->analyticsService->getDailyStats($page),
        };

        return view('analytics.index', compact('page', 'stats', 'period'));
    }

    public function export(Request $request)
    {
        $user = $request->user();
        $page = $user->page;

        if (!$page) {
            return back()->with('error', 'No page found.');
        }

        $data = $this->analyticsService->exportAnalytics($page);

        $filename = "analytics-{$page->slug}-" . now()->format('Y-m-d') . ".csv";

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, ['Date', 'IP Address', 'User Agent', 'Unique Visitor']);

            // Add data
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->visited_at->format('Y-m-d H:i:s'),
                    $row->ip_address,
                    $row->user_agent,
                    $row->is_unique_visitor ? 'Yes' : 'No',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
