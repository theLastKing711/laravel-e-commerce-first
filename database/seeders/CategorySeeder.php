<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * number of categories with null parent is half the specified number
     * number of categories with parent is half the specified number
     */
    public function run(): void
    {
        $itemCount = 10;

        //generate 10 parent categories
        $this->generateParentCategoriesWithProducts($itemCount);

        //generate 10 child categories
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
            ->has(
                Media::factory()->count($count),
                'medially'
                // category is connected to media polymorphiclly through medially
                //as defined in Category.php
            )
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
            ->has(
                Media::factory()->count($count),
                'medially'
            )
            ->has(
                Product::factory()
                    ->state(new Sequence(
                        ['price' => fake()->randomFloat(2, 10, 100)],
                        ['price' => '0.00']
                    ))
                    ->has(
                        Media::factory()->count(2),
                        'medially'
                    )
                    ->has(
                        Variant::factory()
                            ->state(new Sequence(
                                ['name' => 'اللون'],
                                ['name' => 'الحجم'],
                            ))
                            ->has(
                                // has created booted callback that create variantValue combinations each time a variantValue is created
                                VariantValue::factory()
                                    // starts at 4 in the second iteration of the variant factory
                                    ->state(new Sequence(
                                        ['name' => 'أحمر'],
                                        ['name' => 'أصفر'],
                                        ['name' => 'أخضر'],
                                        ['name' => 'صغير'],
                                        ['name' => 'وسط'],
                                        ['name' => 'كبير'],
                                    ))
                                    ->state(function (array $attributes, Variant $variant) {
                                        return [
                                            // 'price' => $variant->product->price === '0.00' ? fake()->randomFloat(2, 10, 100) : '0.00',
                                            'price' => fake()->randomFloat(2, 10, 100),
                                        ];
                                    })
                                    ->has(
                                        Media::factory()->count(1),
                                        'medially'
                                    )
                                    ->count(3)
                            )
                            ->count(2)
                    )
                    ->count($count)
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
