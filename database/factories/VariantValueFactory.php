<?php

namespace Database\Factories;

use App\Models\Variant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\VariantValue>
 */
class VariantValueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'asldkfj',
            'price' => $this->faker->randomFloat(2, 10, 100),
            'available' => $this->faker->numberBetween(0, 5),
        ];
    }

    // public function genereateValueCombinations(): static
    // {
    //     return $this->afterCreating(function (Variant $variant) {

    //         $variant->generateValuesCombinations();

    //     });
    // }
}
