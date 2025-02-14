<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
            'group' => Arr::random(['auth', 'validation', 'messages', 'emails']),
//            'key' => $this->faker->unique()->slug,
            'key' => Str::snake($this->faker->words(3, true)), // such as: "welcome_message_title"
            'value' => $this->faker->sentence,
            'tags' => json_encode($this->faker->randomElements(['mobile', 'desktop', 'web'], rand(1, 3))) // get random tags
        ];
    }
}
