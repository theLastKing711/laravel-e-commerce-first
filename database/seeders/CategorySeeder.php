<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     * number of categories with null parent is half the speciefied number
     * number of categories with parent is half the speciefied number
     */
    public function run(): void
    {
        Category::factory()
                    ->count(16)
                    ->state(new Sequence(
                            ['parent_id' => null],
                            ['parent_id' =>
                                    Category
                                        ::whereParentId(null)
                                        ->get()
                                        ->random()
                            ],
                    ))
                    ->create();
    }
}
