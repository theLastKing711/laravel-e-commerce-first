<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
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
            'name' => fake()->name(),
            'hash' => Str::random(10),
            'is_special' => fake()->boolean(),
            'parent_id' => null
        ];
    }
}
