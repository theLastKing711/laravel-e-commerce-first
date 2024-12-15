<?php

namespace App\Data\User\Order\Create;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\Bail;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class CreateOrderDetailsData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer', default: 1),
        ]
        public int $quantity,
        #[
            OAT\Property(default: '25'),
            RequiredWithout('unit_price_offer'),
            Numeric,
            Min(0)
        ]
        public ?string $unit_price,
        #[
            OAT\Property(default: ''),
            RequiredWithout('unit_price'),
            Numeric,
            Min(0)
        ]
        public ?string $unit_price_offer,
        #[
            OAT\Property(default: 1),
            Bail,
        ]
        public string $product_id,
        #[OAT\Property()]
        public ?string $product_variation_id,
    ) {}
}
