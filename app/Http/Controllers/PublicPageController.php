<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class PublicPageController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function show(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)
            ->where('is_published', true)
            ->with('theme', 'user')
            ->firstOrFail();

        // Track page view
        $this->analyticsService->trackPageView($page, $request);

        return view('public.page', compact('page'));
    }
}
