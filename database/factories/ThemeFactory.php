<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->words(2, true),
            'slug' => $this->faker->unique()->slug,
            'primary_color' => $this->faker->hexColor,
            'secondary_color' => $this->faker->hexColor,
            'background_color' => $this->faker->hexColor,
            'text_color' => $this->faker->hexColor,
            'accent_color' => $this->faker->hexColor,
        ];
    }
}
