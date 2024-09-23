<?php

namespace App\Data\Store\Order;

use App\Models\Order;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class AcceptOrderData extends Data
{
    public function __construct(
        #[
            OAT\Property(),
            Exists(Order::class)
        ]
        public int $id,
    ) {
    }

}
