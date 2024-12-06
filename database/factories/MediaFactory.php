<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_url' => $this->faker->imageUrl(),
            'file_name' => $this->faker->text(),
            'file_type' => $this->faker->randomElement(['jpg', 'jpeg', 'png', 'gif']),
            'size' => $this->faker->numberBetween(9000, 10000),
        ];
    }

    // public function is_category_image(): Factory
    // {

    //     return $this->state([
    //         'file_type' => 'image',
    //     ]);
    // }
}
