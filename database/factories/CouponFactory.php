<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'value' => fake()->numberBetween(5, 90),
            'code' => fake()->randomNumber(6),
            'start_at' => fake()->dateTimeBetween('now', '+1 week'),
            'end_at' => fake()->dateTimeBetween('+2 weeks', '+3 weeks'),
        ];
    }

    public function testCoupon(): static
    {
        return $this->state(fn (array $attributes) => [
            'code' => '123456',
        ]);
    }
}
