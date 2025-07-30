<?php

namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Visitor>
 */
class VisitorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 years', 'now')->format('Y-m-d H:i:s');
        $visitable_type = $this->faker->optional()->randomElement([Post::class]);
        $visitable_id = null;
        if ($visitable_type === Post::class) {
            $visitable_id = rand(1, 100);
        }

        $ip = $this->faker->ipv4();
        $ipInfo = geoip($ip);

        return [
            'visitable_id' => $visitable_id,
            'visitable_type' => $visitable_type,
            'user_id' => $this->faker->optional()->randomElement(range(1, 100)),
            'path' => $this->faker->url(),
            'os' => $this->faker->randomElement(['Windows', 'Linux', 'MacOS', 'Android', 'iOS']),
            'browser' => $this->faker->randomElement(['Chrome', 'Firefox', 'Safari', 'Edge']),
            'user_agent' => $this->faker->userAgent(),
            'ip' => $ip,
            'country' => $ipInfo->country,
            'city' => $ipInfo->city,
            'created_at' => $date,
            'updated_at' => $date,
        ];
    }
}
