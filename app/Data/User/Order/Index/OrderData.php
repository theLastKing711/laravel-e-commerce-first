<?php

namespace App\Data\User\Order\Index;

use App\Data\Shared\Swagger\Property\DateProperty;
use App\Enum\OrderStatus;
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
}
