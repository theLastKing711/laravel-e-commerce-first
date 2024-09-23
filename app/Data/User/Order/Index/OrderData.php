<?php

namespace App\Data\User\Order\Index;

use App\Data\Shared\Swagger\Property\DateProperty;
use App\Enum\OrderStatus;
use App\Models\Order;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class OrderData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public OrderStatus $order_status,
        #[OAT\Property()]
        public float $total,
        #[OAT\Property()]
        public float $items_count,
        #[DateProperty]
        public string $required_time,
        #[DateProperty]
        public string $created_at,
    ) {
    }

    public static function fromModel(Order $order): self
    {
        $total_with_delivery_and_discount = $order->getTotalWithShipmentAndDiscount();

        $items_count = $order->orderDetails->count();

        return new self(
            id: $order->id,
            order_status: OrderStatus::from($order->status),
            total: $total_with_delivery_and_discount,
            items_count: $items_count,
            required_time: $order->required_time,
            created_at: $order->created_at,
        );
    }
}
