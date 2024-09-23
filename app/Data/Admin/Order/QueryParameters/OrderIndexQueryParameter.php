<?php

namespace App\Data\Admin\Order\QueryParameters;

use App\Enum\OrderStatus;
use Spatie\LaravelData\Data;

class OrderIndexQueryParameter extends Data
{
    public function __construct(
        public ?string $search,
        public ?OrderStatus $order_status,
    ) {
    }
}
