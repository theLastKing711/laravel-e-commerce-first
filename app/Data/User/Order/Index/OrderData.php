<?php

namespace App\Data\User\Order\Index;

use App\Enum\OrderStatus;
use App\Models\Order;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'userOrderData')]
class OrderData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'integer')]
        public int $id,
        #[OAT\Property()]
        public OrderStatus $order_status,
        #[OAT\Property(type: 'number')]
        public float $total,
        #[OAT\Property(type: 'integer')]
        public float $items_count,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $required_time,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
    ) {
    }

    public static function fromModel(Order $order): self
    {
        $total = $order->getTotalWithShipmentAndDiscount();

        $items_count = $order->orderDetails->count();

        return new self(
            id: $order->id,
            order_status: OrderStatus::from($order->status),
            total: $total,
            items_count: $items_count,
            required_time: $order->required_time,
            created_at: $order->created_at,
        );
    }
}
