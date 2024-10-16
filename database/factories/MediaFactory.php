<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MediaFactory extends Factory
{
    private string $category_model_path = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
//            'medially_id' => Category::factory(), // are auto generated based on parent relation
//            'medially_type' => $this->category_model_path,
// // are auto generated based on parent relation
            'file_url' => $this->faker->imageUrl(),
            'file_name' => $this->faker->text(),
            'file_type' => $this->faker->randomElement(['jpg', 'jpeg', 'png', 'gif']),
            'size' => $this->faker->numberBetween(9000, 10000),
        ];
    }

    public function is_category_image(): Factory
    {

        return $this->state([
            'file_type' => 'image',
        ]);
    }
}
