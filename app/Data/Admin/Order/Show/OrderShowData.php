<?php

namespace App\Data\Admin\Order\Show;

use App\Data\Admin\Order\Show\OrderDetails\OrderDetailsData;
use App\Enum\OrderStatus;
use App\Models\Order;
use App\Models\OrderDetails;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminOrderShow')]
class OrderShowData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'integer')]
        public int $id,
        #[OAT\Property(type: 'string')]
        public string $total,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $required_time,
        #[
            OAT\Property(),
        ]
        public OrderStatus $status,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $lat,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $lon,
        #[OAT\Property(type: 'string')]
        public string $delivery_price,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: OrderDetailsData::class,
            )
        )]
        /** @var Collection<int, OrderDetailsData> */
        public Collection $items,
    ) {
    }

    public static function fromModel(Order $order): self
    {

        $order_details = $order->orderDetails;

        return new self(
            id: $order->id,
            total: $order->total,
            required_time: $order->required_time,
            status: OrderStatus::from($order->status),
            lat: $order->lat,
            lon: $order->lon,
            delivery_price: $order->delivery_price,
            created_at: $order->created_at,
            items: OrderDetailsData::collect($order_details->map(function (OrderDetails $orderDetail) {
                return new OrderDetailsData(
                    id: $orderDetail->id,
                    quantity: $orderDetail->quantity,
                    unit_price: $orderDetail->unit_price * $orderDetail->quantity,
                    image: $orderDetail->product?->image,
                );
            })),
        );
    }
}
