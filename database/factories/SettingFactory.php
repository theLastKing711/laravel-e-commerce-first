<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends Factory<Model>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'km_price' => 10,
            'open_km_price' => 10,
            'order_delivery_min_distance' => 10,
            'order_delivery_min_item_per_order' => 2,
            'min_order_item_quantity_for_free_delivery' => 5,
            'store_lat' => '36.223721',
            'store_lon' => '37.129631',
            'address' => 'syria,Aleppo',
        ];
    }
}
