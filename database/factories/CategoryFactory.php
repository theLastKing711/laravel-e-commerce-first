<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Support\Facades\Log;
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
        //        $parent_ids = Category
        //                        ::select('parent_id')
        //                        ->get()
        //                        ->toArray();

        //        $rand_parent_id_key = array_rand($parent_ids, 1);

        //        $rand_parent_id = $parent_ids[$rand_parent_id_key];

        return [
            'id' => fake()->unique()->randomNumber(6),
            'name' => fake()->name(),
            'hash' => Str::random(10),
            'is_special' => fake()->boolean(),
            'parent_id' => null,
        ];
    }

    /**
     * Indicate that this category is a parent one,
     * with parent_id set to null`.
     */
    public function parent(): Factory
    {
        return $this->state([
            'parent_id' => null,
        ]);
    }

    /**
     * Indicate that this category is a child one,
     * with parent_id set to a value other than null`.
     */
    public function child(): Factory
    {

        return $this->state(new Sequence(
            function (Sequence $sequence) {
                //                Log::info('random category {category} ', ['category' => $this->getRandomParentCategoryId()]);
                //                $random_parent_id = $this->faker->randomElement(Category::isParent()->pluck('id')->toArray());

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
