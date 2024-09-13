<?php

namespace App\Data\Admin\Order\Show\OrderDetails;

use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Computed;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminOrderDetails')]
class OrderDetailsData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'integer')]
        public int $id,
        #[OAT\Property(type: 'integer')]
        public int $quantity,
        #[OAT\Property(type: 'string')]
        public string $unit_price,
        #[
            OAT\Property(type: 'string'),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'product')
        ]
        public ?string $image,
    ) {
    }

}
