<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run()
    {
        $themes = [
            [
                'name' => 'Ocean Blue',
                'slug' => 'ocean-blue',
                'primary_color' => '#1E40AF',
                'secondary_color' => '#3B82F6',
                'background_color' => '#F0F9FF',
                'text_color' => '#1F2937',
                'accent_color' => '#60A5FA',
            ],
            [
                'name' => 'Forest Green',
                'slug' => 'forest-green',
                'primary_color' => '#065F46',
                'secondary_color' => '#10B981',
                'background_color' => '#F0FDF4',
                'text_color' => '#1F2937',
                'accent_color' => '#34D399',
            ],
            [
                'name' => 'Sunset Orange',
                'slug' => 'sunset-orange',
                'primary_color' => '#C2410C',
                'secondary_color' => '#F97316',
                'background_color' => '#FFF7ED',
                'text_color' => '#1F2937',
                'accent_color' => '#FB923C',
            ],
            [
                'name' => 'Purple Dream',
                'slug' => 'purple-dream',
                'primary_color' => '#6B21A8',
                'secondary_color' => '#A855F7',
                'background_color' => '#FAF5FF',
                'text_color' => '#1F2937',
                'accent_color' => '#C084FC',
            ],
            [
                'name' => 'Classic Dark',
                'slug' => 'classic-dark',
                'primary_color' => '#1F2937',
                'secondary_color' => '#4B5563',
                'background_color' => '#111827',
                'text_color' => '#F9FAFB',
                'accent_color' => '#9CA3AF',
            ],
            [
                'name' => 'Rose Gold',
                'slug' => 'rose-gold',
                'primary_color' => '#B76E79',
                'secondary_color' => '#F1A7A7',
                'background_color' => '#FFF5F7',
                'text_color' => '#1F2937',
                'accent_color' => '#FDB7C0',
            ],
            [
                'name' => 'Slate Minimal',
                'slug' => 'slate-minimal',
                'primary_color' => '#334155',
                'secondary_color' => '#64748B',
                'background_color' => '#F8FAFC',
                'text_color' => '#0F172A',
                'accent_color' => '#94A3B8',
            ],
            [
                'name' => 'Emerald Light',
                'slug' => 'emerald-light',
                'primary_color' => '#059669',
                'secondary_color' => '#34D399',
                'background_color' => '#ECFDF5',
                'text_color' => '#064E3B',
                'accent_color' => '#6EE7B7',
            ],
        ];

        foreach ($themes as $theme) {
            Theme::updateOrCreate(['slug' => $theme['slug']], $theme);
        }
    }
}
