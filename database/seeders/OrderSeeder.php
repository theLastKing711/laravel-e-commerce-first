<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $orders = Order::factory()
            ->has(
                OrderDetails::factory()
                    ->count(3)
            )
            ->has(
                OrderDetails::factory()
                    ->oneVariantProduct()
                    ->count(2)
            )
            ->has(
                OrderDetails::factory()
                    ->twoVariantsProduct()
                    ->count(2)
            )
            ->has(
                OrderDetails::factory()
                    ->threeVariantProduct()
                    ->count(1)
            )
            ->count(5)
            ->updateTotalUsingItems()
            ->create();
    }
}
