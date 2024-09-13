<?php

namespace Database\Factories;

use App\Enum\Auth\RolesEnum;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => OrderStatus::Pending,
            'total' => 0,
            'required_time' => fake()->dateTime(),
            'lat' => 0,
            'lon' => 0,
            'delivery_price' => fake()->randomFloat(2, 10, 100),
            'user_id' => $this->getRandomUser()->id,
        ];
    }

    public function getRandomUser(): User
    {
        return User::role(RolesEnum::USER->value)
            ->inRandomOrder()
            ->first();
    }

    public function updateTotalUsingItems(): static
    {
        return $this->afterCreating(function (Order $order) {
            $order->total = $order->orderDetails
                ->sum(
                    fn (OrderDetails $orderDetails) => $orderDetails->quantity * $orderDetails->unit_price
                );
            $order->save();
        });
    }
}
