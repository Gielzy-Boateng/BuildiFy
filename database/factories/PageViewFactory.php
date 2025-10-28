<?php

namespace Database\Factories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Factories\Factory;

class PageViewFactory extends Factory
{
    public function definition()
    {
        return [
            'page_id' => Page::factory(),
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'session_id' => $this->faker->uuid,
            'is_unique_visitor' => $this->faker->boolean(70),
            'visited_at' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
