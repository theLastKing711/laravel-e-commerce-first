<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\VariantValue;
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
            'is_thumb' => false,
        ];
    }

    public function withImage(): static
    {
        return $this->afterCreating(function (VariantValue $variant) {

            $media = Media::factory(1)
                ->makeOne();

            $variant
                ->medially()
                ->saveMany([$media]);

        });
    }
}
