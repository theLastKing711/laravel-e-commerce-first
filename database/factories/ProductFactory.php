<?php

namespace Database\Factories;

use App\Enum\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
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
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(2, 10, 100),
            'is_most_buy' => $this->faker->boolean(),
            'is_favourite' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'unit' => $this->faker->randomElement(Unit::cases()),
            'unit_value' => $this->faker->randomFloat(2, 0, 100),
        ];
    }
}
