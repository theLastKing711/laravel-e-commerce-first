<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderDetails>
 */
class OrderDetailsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $randomProduct = $this->getRandomProduct();

        return [
            'product_id' => $randomProduct->id,
            'unit_price' => $randomProduct->price,
            'quantity' => fake()->numberBetween(1, 25),
        ];
    }

    public function getRandomProduct()
    {
        return Product::all()->random();
    }
}
