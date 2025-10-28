<?php

namespace Tests\Unit;

use App\Models\Page;
use App\Models\PageView;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnalyticsServiceTest extends TestCase
{
    use RefreshDatabase;

    protected $analyticsService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->analyticsService = new AnalyticsService();
    }

    public function test_can_get_total_views()
    {
        $page = Page::factory()->create();
        PageView::factory()->count(5)->create(['page_id' => $page->id]);

        $totalViews = $page->getTotalViews();

        $this->assertEquals(5, $totalViews);
    }

    public function test_can_get_unique_views()
    {
        $page = Page::factory()->create();

        PageView::factory()->count(3)->create([
            'page_id' => $page->id,
            'is_unique_visitor' => true,
        ]);

        PageView::factory()->count(2)->create([
            'page_id' => $page->id,
            'is_unique_visitor' => false,
        ]);

        $uniqueViews = $page->getUniqueViews();

        $this->assertEquals(3, $uniqueViews);
    }

    public function test_can_get_daily_stats()
    {
        $page = Page::factory()->create();

        PageView::factory()->count(5)->create([
            'page_id' => $page->id,
            'visited_at' => now(),
        ]);

        $dailyStats = $this->analyticsService->getDailyStats($page, 7);

        $this->assertNotEmpty($dailyStats);
    }
}
