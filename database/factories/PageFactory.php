<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Theme;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence,
            'slug' => $this->faker->unique()->slug,
            'content' => [
                'hero_heading' => $this->faker->sentence,
                'hero_subheading' => $this->faker->sentence,
                'about_heading' => 'About Us',
                'about_text' => $this->faker->paragraph,
                'services_heading' => 'Our Services',
                'service_1_title' => $this->faker->words(3, true),
                'service_1_description' => $this->faker->sentence,
                'service_2_title' => $this->faker->words(3, true),
                'service_2_description' => $this->faker->sentence,
                'service_3_title' => $this->faker->words(3, true),
                'service_3_description' => $this->faker->sentence,
                'contact_heading' => 'Contact Us',
                'contact_text' => $this->faker->sentence,
            ],
            'theme_id' => Theme::factory(),
            'is_published' => false,
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_published' => true,
                'published_at' => now(),
            ];
        });
    }
}
