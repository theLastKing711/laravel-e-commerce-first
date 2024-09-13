<?php

namespace App\Data\User\Order\Show;

use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'userShowOrder')]
class OrderShowData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer'),
        ]
        public int $id,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $required_time,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $receiver_name,
        #[
            OAT\Property(type: 'number'),
        ]
        public float $total,
        #[
            OAT\Property(type: 'number'),
        ]
        public float $discount,
        #[
            OAT\Property(type: 'number'),
        ]
        public float $delivery_price,
        #[
            OAT\Property(type: 'number'),
        ]
        public string $driver_lat,
        #[
            OAT\Property(type: 'number'),
        ]
        public string $driver_lon,
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: OrderShowItemData::class,
            )
        )]
        /** @var Collection<int, OrderShowItemData> */
        public Collection $items,
        public string $created_at,
    ) {
    }

    public static function fromModel(Order $order): self
    {
        $order_total_with_shipment_and_delivery = $order->getTotalWithShipmentAndDiscount();

        $order_items = $order->orderDetails;

        $coupon_discount_value = $order->coupon?->value ?? 0;

        $driver_lat = $order->driver?->lat ?? 0;

        $driver_lon = $order->driver?->lon ?? 0;

        return new self(
            id: $order->id,
            required_time: $order->required_time,
            receiver_name: $order->user->name,
            total: $order_total_with_shipment_and_delivery,
            discount: $coupon_discount_value,
            delivery_price: $order->delivery_price,
            driver_lat: $driver_lat,
            driver_lon: $driver_lon,
            items: $order_items->map(function (OrderDetails $orderItem) {

                $product = $orderItem->product;

                return new OrderShowItemData(
                    id: $orderItem->id,
                    quantity: $orderItem->quantity,
                    price: $product->price ?? $product->price_offer,
                    name: $orderItem->product->name,
                    image: $product->image,
                );
            }),
            created_at: $order->created_at,
        );
    }
}
