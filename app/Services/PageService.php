<?php

namespace App\Services;

use App\Models\Page;
use App\Models\PageVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PageService
{
    public function createPage($user, array $data)
    {
        $page = Page::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'slug' => $this->generateUniqueSlug($data['title'], $user->slug),
            'content' => $data['content'] ?? $this->getDefaultContent(),
            'theme_id' => $data['theme_id'] ?? 1,
            'is_published' => false,
        ]);

        return $page;
    }

    public function updatePage(Page $page, array $data)
    {
        // Create version history before updating
        if (isset($data['content']) || isset($data['theme_id'])) {
            $this->createVersion($page, $data['change_description'] ?? 'Updated page content');
        }

        $page->update($data);

        return $page->fresh();
    }

    public function publishPage(Page $page)
    {
        $page->update([
            'is_published' => true,
            'published_at' => now(),
        ]);

        return $page;
    }

    public function unpublishPage(Page $page)
    {
        $page->update([
            'is_published' => false,
        ]);

        return $page;
    }

    public function uploadImage($file, $type = 'general')
    {
        $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs("pages/{$type}", $filename, 'public');

        return Storage::url($path);
    }

    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    private function createVersion(Page $page, $description = null)
    {
        PageVersion::create([
            'page_id' => $page->id,
            'user_id' => $page->user_id,
            'content' => $page->content,
            'theme_id' => $page->theme_id,
            'change_description' => $description,
        ]);
    }

    private function generateUniqueSlug($title, $userSlug)
    {
        $slug = Str::slug($title);
        $fullSlug = "{$userSlug}-{$slug}";

        $count = 1;
        while (Page::where('slug', $fullSlug)->exists()) {
            $fullSlug = "{$userSlug}-{$slug}-{$count}";
            $count++;
        }

        return $fullSlug;
    }

    private function getDefaultContent()
    {
        return [
            'hero_heading' => 'Welcome to Our Website',
            'hero_subheading' => 'We provide excellent services',
            'about_heading' => 'About Us',
            'about_text' => 'We are a company dedicated to excellence.',
            'services_heading' => 'Our Services',
            'service_1_title' => 'Service One',
            'service_1_description' => 'Description of service one',
            'service_2_title' => 'Service Two',
            'service_2_description' => 'Description of service two',
            'service_3_title' => 'Service Three',
            'service_3_description' => 'Description of service three',
            'contact_heading' => 'Contact Us',
            'contact_text' => 'Get in touch with us today',
        ];
    }
}
