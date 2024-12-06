<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantValue;
use App\Services\VariantValue\VariantValueCreationService;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function __construct(public VariantValueCreationService $variantValueCreationService)
    {

    }

    /**
     * Run the database seeds.
     * number of categories with null parent is half the specified number
     * number of categories with parent is half the specified number
     */
    public function run(): void
    {
        $itemCount = 20;

        $this->generateParentCategoriesWithProducts($itemCount);

        $this->generateChildCategoriesWithProducts($itemCount);
    }

    /**
     * create Categories with no parent id aka Parent Categories
     * has Child Categories, and no child Products
     */
    public function generateParentCategoriesWithProducts(int $count): void
    {

        $seededParentCategories = Category::factory()
            ->parent()
            // ->has(
            //     Media::factory()->count(1),
            //     'medially'
            //     // category is connected to media polymorphiclly through medially
            //     //as defined in Category.php
            // )
            ->count($count)
            ->create();

    }

    /**
     *has Multiple parent Cateogires, and child Products
     */
    public function generateChildCategoriesWithProducts(int $count): void
    {

        $seededChildCategories = Category::factory()
            ->child()
            // ->has(
            //     Media::factory()->count(1),
            //     'medially'
            // )
            ->has(
                Product::factory()
                    ->state(new Sequence(
                        ['price' => fake()->randomFloat(2, 10, 100)],
                        // ['price' => '0.00']
                    ))
                    ->has(
                        Media::factory()->count(2),
                        'medially'
                    )
                    ->afterCreating(function (Product $product) {

                        $VARIANTS = ['اللون', 'الحجم', 'اللمسة'];

                        $variant_map = [
                            'اللون' => [
                                'أحمر',
                                'أزرق',
                            ],
                            'الحجم' => [
                                'صغير',
                                'وسط',
                            ],
                            'اللمسة' => [
                                'مات',
                                'نيون',
                            ],
                        ];

                        // $random_variant = key(fake()->randomElement($variant_map)); //returns color,size or finish.

                        $shuffled_variants = fake()->shuffleArray($VARIANTS);

                        $variants = Variant::factory()
                            ->state(new Sequence(
                                ['product_id' => $product->id, 'name' => $shuffled_variants[0]],
                                ['product_id' => $product->id, 'name' => $shuffled_variants[1]],
                                ['product_id' => $product->id, 'name' => $shuffled_variants[2]],
                            ))
                            ->has(
                                // has created booted callback that create variantValue combinations each time a variantValue is created
                                VariantValue::factory()
                                    // starts at 3 in the second iteration of the variant factory
                                    // count 3 * 2 = 6 items max.
                                    ->state(
                                        new Sequence(
                                            ['name' => $variant_map[$shuffled_variants[0]][0], 'is_thumb' => true],
                                            ['name' => $variant_map[$shuffled_variants[0]][1], 'is_thumb' => false],
                                            ['name' => $variant_map[$shuffled_variants[1]][0], 'is_thumb' => false],
                                            ['name' => $variant_map[$shuffled_variants[1]][1], 'is_thumb' => true],
                                            ['name' => $variant_map[$shuffled_variants[2]][0], 'is_thumb' => false],
                                            ['name' => $variant_map[$shuffled_variants[2]][1], 'is_thumb' => true],
                                        )
                                    )
                                    ->state(function (array $attributes, Variant $variant) {
                                        return [
                                            // 'price' => $variant->product->price === '0.00' ? fake()->randomFloat(2, 10, 100) : '0.00',
                                            'price' => fake()->randomFloat(2, 10, 100),
                                        ];
                                    })
                                    ->afterCreating(function (VariantValue $variantValue) {
                                        $this
                                            ->variantValueCreationService
                                            ->onVariantValueCreated($variantValue);
                                    })
                                    ->has(
                                        Media::factory()->count(1),
                                        'medially'
                                    )
                                    ->count(2)
                            )
                            ->count(rand(0, 3))
                            ->create();

                        $product
                            ->variants()
                            ->saveMany($variants);
                    })
            )
            ->count($count)
            ->create();

    }

    public static function generateTestCategories(int $count): void
    {

        static::generateParentCategories(10);
        static::generateChildCategories(10);

    }

    public static function generateParentCategories(int $count): void
    {
        $seededParentCategories = Category::factory()
            ->has(
                Media::factory()->count($count),
                'medially'
            )
            ->parent()
            ->count($count)
            ->create();

    }

    /**
     *has Multiple parent Categorises, and child Products
     */
    public static function generateChildCategories(int $count): void
    {

        $seededChildCategories = Category::factory()
            ->has(
                Media::factory()->count($count),
                'medially'
            )
            ->child()
            ->count($count)
            ->create();

    }
}
