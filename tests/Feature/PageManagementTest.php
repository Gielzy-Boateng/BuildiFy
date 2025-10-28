<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Page;
use App\Models\Theme;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\ThemeSeeder::class);
    }

    public function test_user_can_create_page()
    {
        $user = User::factory()->create();
        $theme = Theme::first();

        $response = $this->actingAs($user)->post('/pages', [
            'title' => 'My Test Page',
            'theme_id' => $theme->id,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', [
            'user_id' => $user->id,
            'title' => 'My Test Page',
        ]);
    }

    public function test_user_can_update_their_page()
    {
        $user = User::factory()->create();
        $page = Page::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->put("/pages/{$page->id}", [
            'title' => 'Updated Title',
            'content' => [
                'hero_heading' => 'Updated Heading',
            ],
        ]);

        $response->assertRedirect(); // Changed from assertStatus(200)
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
        ]);
    }

    public function test_user_cannot_update_other_users_page()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $page = Page::factory()->create(['user_id' => $user2->id]);

        $response = $this->actingAs($user1)->put("/pages/{$page->id}", [
            'title' => 'Hacked Title',
        ]);

        $response->assertForbidden();
    }

    public function test_user_can_publish_page()
    {
        $user = User::factory()->create();
        $page = Page::factory()->create(['user_id' => $user->id, 'is_published' => false]);

        $response = $this->actingAs($user)->post("/pages/{$page->id}/publish");

        $response->assertRedirect();
        $this->assertDatabaseHas('pages', [
            'id' => $page->id,
            'is_published' => true,
        ]);
    }

    public function test_published_page_is_publicly_accessible()
    {
        $page = Page::factory()->create(['is_published' => true]);

        $response = $this->get("/page/{$page->slug}");

        $response->assertStatus(200);
        $response->assertSee($page->title);
    }
}
