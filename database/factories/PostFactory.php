<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $time = Carbon::createFromDate(rand(2022, 2023), rand(1, 12), rand(1, 28));

        return [
            'title' => fake()->title(),
            'category_id' => rand(1, 2),
            'created_at' => $time,
            'updated_at' => $time,
            'content' => fake()->paragraph(),
        ];
    }
}
