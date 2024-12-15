<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => fake()->unique()->randomNumber(6),
            'name' => fake()->name(),
            'hash' => Str::random(10),
            'is_special' => fake()->boolean(),
            'parent_id' => null,
        ];
    }

    public function parent(): Factory
    {
        return $this->state([
            'parent_id' => null,
        ]);
    }

    public function child(): Factory
    {
        return $this->state(new Sequence(
            function (Sequence $sequence) {

                return ['parent_id' => $this->getRandomParentCategoryId()];
            }
        ));
    }

    public function getRandomParentCategoryId()
    {
        return Category::whereParentId(null)
            ->pluck('id')
            ->random();
    }
}
