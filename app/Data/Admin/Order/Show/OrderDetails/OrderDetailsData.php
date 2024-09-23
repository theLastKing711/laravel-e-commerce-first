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
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public int $quantity,
        #[OAT\Property()]
        public string $unit_price,
        #[
            OAT\Property(),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'product')
        ]
        public ?string $image,
    ) {
    }

}
