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
                    ->count(10)
            )
            ->count(10)
            ->updateTotalUsingItems()
            ->create();
    }
}
