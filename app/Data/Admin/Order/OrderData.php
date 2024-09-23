<?php

namespace App\Data\Admin\Order;

use App\Enum\OrderStatus;
use App\Models\Order;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminOrder')]
class OrderData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public ?string $customer_name,
        #[OAT\Property()]
        public ?string $driver_name,
        #[OAT\Property()]
        public string $total,
        #[OAT\Property()]
        public OrderStatus $status,
    ) {
    }

    public static function fromModel(Order $order): self
    {

        return new self(
            id: $order->id,
            customer_name: $order->user?->name,
            driver_name: $order->driver?->name,
            total: $order->total,
            status: OrderStatus::from($order->status),
        );
    }
}
