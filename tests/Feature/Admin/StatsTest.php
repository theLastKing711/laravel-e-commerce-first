<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\Admin\Abstractions\AdminTestCase;

class StatsTest extends AdminTestCase
{
    private string $main_route = '/admin/stats';

    public User $user;

    private function CreateUser(): void
    {
        $this->user = User::factory()
            ->user()
            ->create();
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->seed([UserSeeder::class]);

        //        $this->setUpOrdersManually();

        $this->setUpOrders();

        //        Log::info(
        //            Order::with(
        //                [
        //                    'orderDetails' => [
        //                        'product' => [
        //                            'categories' => [
        //                                'parent',
        //                            ],
        //                        ],
        //                    ],
        //                ]
        //            )->first()
        //        );

    }

    private function setUpOrders(): void
    {

        $parent_categories = Category::factory()
            ->parent()
            ->count(3)
            ->create();

        Log::info('parent categories {categories}', ['categories' => $parent_categories]);

        //        Log::info(Category::isParent()->first());

        $child_categories = Category::factory()
            ->child()
            ->count(3)
//            ->recycle($parent_categories)
            ->create();

        Log::info('child categories {categories}', ['categories' => Category::isChild()->first()]);

        //        Log::info(Category::isParent()->with('children')->first());
        Log::info('child categories {categories}', ['categories' => $child_categories->pluck('parent_id')]);

        $products = Product::factory()
            ->count(3)
            ->afterCreating(function (Product $product) use ($child_categories) {
                $product_child_categories_ids = $child_categories
                    ->random(rand(1, 3))
                    ->pluck('id');
                $product->categories()->attach($product_child_categories_ids);
            })
            ->create();

        $orders = Order::factory()
            ->count(3)
            ->has(
                OrderDetails::factory()
                    ->count(3)
                    ->recycle($products)
            )
            ->create();

        Log::info($orders);

        //        Order::factory()
        //            ->has(
        //                OrderDetails::factory()
        //                    ->count(4)
        //                    ->for(
        //                        Product::factory()
        //                            ->has(
        //                                Category::factory()
        //                                    ->child()
        //                                    ->count(2)
        //                                    ->for(
        //                                        Category::factory()
        //                                            ->parent(),
        //                                        'parent'
        //                                    )
        //                            )
        //                    )
        //            )
        //            ->create();

    }

    public function setUpOrdersManually()
    {

        $first_parent_category = Category::factory()->parent()->state(['name' => 'first parent category'])->createOne();
        $first_child_category = Category::factory()->child()->state(['name' => 'first child category'])->createOne();
        $second_child_category = Category::factory()->child()->state(['name' => 'second child category'])->createOne();
        $third_child_category = Category::factory()->child()->state(['name' => 'third child category'])->createOne();
        $first_parent_category->children()->saveMany([
            $first_child_category,
            $second_child_category,
            $third_child_category,
        ]);

        $second_parent_category = Category::factory()->parent()->state(['name' => 'second child cateogry'])->createOne();
        $fourth_child_category = Category::factory()->child()->state(['name' => 'fourth child category'])->createOne();
        $second_parent_category->children()->saveMany([
            $third_child_category,
            $fourth_child_category,
        ]);

        $first_product = Product::factory()
            ->state(['name' => 'first product', 'price' => 100])
            ->createOne();

        $first_product->categories()->saveMany([
            $first_child_category, //category group 1
            $second_child_category, // category group 1
            $fourth_child_category, // category group 2
        ]);

        $second_product = Product::factory()->state(['name' => 'second product', 'price' => 50])->createOne();
        $second_product->categories()->save(
            $first_child_category // category group 1
        );

        $third_product = Product::factory()->state(['name' => 'third product', 'price' => 10])->createOne();
        $third_product->categories()->save(
            $fourth_child_category // category group 2
        );

        $fourth_product = Product::factory()->state(['name' => 'fourth product', 'price' => 5])->createOne();
        $fourth_product->categories()->save(
            $fourth_child_category // category group 2
        );

        $third_parent_category = Category::factory()->parent()->state(['name' => 'third parent category'])->createOne();

        $first_order = Order::factory()->create();
        $first_order_details = OrderDetails::factory()
            ->state(['unit_price' => $first_product->price, 'quantity' => 2, 'product_id' => $first_product->id])
            //price 100, first category and second category
            ->makeOne();
        $second_order_details = OrderDetails::factory()
            ->state(['unit_price' => $second_product->price, 'quantity' => 3, 'product_id' => $second_product->id])
            // price 50, first category
            ->makeOne();
        $third_order_details = OrderDetails::factory()
            ->state(['unit_price' => $third_product->price, 'quantity' => 4, 'product_id' => $third_product->id])
            // price 10, second category
            ->makeOne();
        $first_order->orderDetails()->saveMany([
            $first_order_details,
            $second_order_details,
            $third_order_details,
        ]);

        $second_order = Order::factory()->create();
        $fourth_order_details = OrderDetails::factory()
            ->state(['unit_price' => $first_product->price, 'quantity' => 1, 'product_id' => $first_product->id])
            //price 100, first category and second category
            ->makeOne();
        $fifth_order_details = OrderDetails::factory()
            ->state(['unit_price' => $third_product->price, 'quantity' => 3, 'product_id' => $third_product->id])
            // price 10, second category
            ->makeOne();
        $second_order->orderDetails()->saveMany([
            $fourth_order_details,
            $fifth_order_details,
        ]);

        Log::info('second order {order}', ['order' => $second_order->with('orderDetails')->get()]);
    }

    /**
     * A basic feature test example.
     */
    #[Test]
    public function index_return_a_list_of_users_with_200_response(): void
    {

        $response = $this->get($this->main_route.'?start_at=2025-10-07&end_at=2026-10-07');

        $response->assertStatus(200);

        $json_response_collection = collect($response->json());

        //        $first_category_stats = $json_response_collection
        //            ->where('name', 'first parent category')
        //            ->first();
        //
        //        $second_category_stats = $json_response_collection
        //            ->where('name', 'second parent category')
        //            ->first();
        //
        //        $third_category_stats = $json_response_collection
        //            ->where('name', 'third parent category')
        //            ->first();
        //
        Log::info('category stats {stats}', ['stats' => $response->json()]);

        $order = Order::with([
            'orderDetails' => [
                'product' => [
                    'categories' => [
                        'parent',
                    ],
                ],
            ],
        ])
            ->get();

        //        $is_saved = \Storage::disk('public')->put('imagesss.json', json_encode($order));

        Log::info(json_encode($response->json()));
        if ($is_saved) {
            Log::info('is saved');
        }

        //
        //        $this->assertEquals(450, $first_category_stats['total_products_revenue_for_category']);
        //        $this->assertEquals(6, $first_category_stats['total_products_sold_for_category']);
        //
        //        $this->assertEquals(370, $second_category_stats['total_products_revenue_for_category']);
        //        $this->assertEquals(10, $second_category_stats['total_products_sold_for_category']);
        //
        //        $this->assertEquals(0, $third_category_stats['total_products_revenue_for_category']);
        //        $this->assertEquals(0, $third_category_stats['total_products_sold_for_category']);

        //        $first_child_category_stats = $json_response_collection
        //            ->where('name', 'first child category')
        //            ->first();
        //
        //        $third_child_category_stats = $json_response_collection
        //            ->where('name', 'third child category')
        //            ->first();
        //
        //        $this->assertEquals(6, $first_child_category_stats['child_category_total_products_sold']);
        //        $this->assertEquals(0, $third_child_category_stats['child_category_total_products_sold']);
        //        $first_product_stats = $json_response_collection
        //            ->where('name', 'first product')
        //            ->first();
        //
        //        $this->assertEquals(300, $first_product_stats['total_sales']);
        //        $this->assertEquals(3, $first_product_stats['total_quantity']);
        //
        //        $fourth_product_stats = $json_response_collection
        //            ->where('name', 'fourth product')
        //            ->first();
        //
        //        $this->assertEquals(0, $fourth_product_stats['total_sales']);
        //        $this->assertEquals(0, $fourth_product_stats['total_quantity']);

        //        $response
        //            ->assertJson(
        //                fn (AssertableJson $json) => $json->has(
        //                    'data',
        //                    10,
        //                    fn (AssertableJson $json) => $json // runs one first item of json('data')
        ////                        ->tap(fn (AssertableJson $json) => Log::info($json))
        //                    ->where('id', $first_db_parent_category->id)
        //                        ->where('name', $first_db_parent_category->name)
        //                        ->where('parent_id', $first_db_parent_category->parent_id)
        //                        ->where('parent_name', $first_db_parent_category->parent?->name)
        //                        ->etc() // means don't need to specify all properties in json('data') here
        //                )
        //                    ->etc()// means don't need to specify all properties in json here
        //            );

    }
}
