<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * number of categories with null parent is half the speciefied number
     * number of categories with parent is half the speciefied number
     */
    public function run(): void
    {
        $itemCount = 10;

        $this->generateParentCategories($itemCount);

        $this->generateChildCategories($itemCount);
    }

    /**
     * create Categories with no parent id aka Parent Categories
     * has Child Categories, and no child Products
     */
    public function generateParentCategories(int $count): void
    {
        $seededParentCategories = Category::factory()
            ->count($count)
            ->parent()
            ->create();
        //
        //        Log::info(
        //            'parent categories seeded {seededCategories} ',
        //            ['seededCategories' => $seededParentCategories]
        //        );

    }

    /**
     *has Multiple parent Cateogires, and child Products
     */
    public function generateChildCategories(int $count): void
    {

        //        Log::info('all Categories {categories} ', ['categories' => Category::all()]);

        $seededChildCategories = Category::factory()
            ->has(
                Product::factory()->count($count)
                    ->state(function (array $attributes, Category $category) {
                        return ['category_id' => $category->id];
                    })
            )
            ->child()
            ->count($count)
            ->create();


        //        Log::info(
        //            'child categories seeded {seededCategories} ',
        //            ['seededCategories' => $seededChildCategories]
        //        );
    }
}
