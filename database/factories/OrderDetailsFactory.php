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

    public function oneVariantProduct()
    {
        /** @var Product $random_one_variant_product */
        $random_one_variant_product =
            Product::query()
                ->has('variants', 1)
                ->with([
                    'variants' => [
                        'variantValues',
                    ],
                ])
                ->first();

        $product_random_variant_value =
            $random_one_variant_product
                ->variants
                ->shuffle()
                ->first()
                ->variantValues
                ->shuffle()
                ->first();

        return $this->state([
            'product_id' => $random_one_variant_product->id,
            'variant_value_id' => $product_random_variant_value->id,
            'unit_price' => $product_random_variant_value->price,
        ]);
    }

    public function twoVariantsProduct()
    {
        /** @var Product $random_two_variants_product */
        $random_two_variants_product =
            Product::query()
                ->has('variants', 2)
                ->with([
                    'variants' => [
                        'variantValues' => [
                            'combinations',
                        ],
                    ],
                ])
                ->first();

        $product_random_variant_combination =
            $random_two_variants_product
                ->variants
                ->first()
                ->variantValues
                ->shuffle()
                ->first()
                ->combined_by
                ->shuffle()
                ->first()
                ->pivot;

        return $this->state([
            'product_id' => $random_two_variants_product->id,
            'variant_combination_id' => $product_random_variant_combination->id,
            'unit_price' => $product_random_variant_combination->price,
        ]);
    }

    public function threeVariantProduct()
    {

        /** @var Product $random_three_variants_product */
        $random_three_variants_product =
            Product::query()
                ->has('variants', 3)
                ->with([
                    'variants' => [
                        'variantValues' => [
                            'combinations' => [
                                'pivot' => [
                                    'combinations' => [
                                        'pivot',
                                    ],
                                ],
                            ],
                            'combined_by' => [
                                'pivot' => [
                                    'combinations' => [
                                        'pivot',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])
                ->first();

        $product_random_second_variant_combination =
            $random_three_variants_product
                ->variants
                ->first()
                ->variantValues
                ->shuffle()
                ->first()
                ->combined_by
                ->shuffle()
                ->first()
                ->pivot
                ->combinations
                ->shuffle()
                ->first()
                ->pivot;

        return $this->state([
            'product_id' => $random_three_variants_product->id,
            'second_variant_combination_id' => $product_random_second_variant_combination->id,
            'unit_price' => $product_random_second_variant_combination->price,

        ]);
    }

    public function getRandomProduct()
    {
        return Product::all()->random();
    }
}
