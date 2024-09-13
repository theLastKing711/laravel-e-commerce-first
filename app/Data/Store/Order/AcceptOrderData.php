<?php

namespace App\Data\Store\Order;

use App\Enum\OrderStatus;
use App\Models\Order;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'storeAcceptOrder')]
class AcceptOrderData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer'),
            In(Order::class)
        ]
        public int $id,
    ) {
    }

}
