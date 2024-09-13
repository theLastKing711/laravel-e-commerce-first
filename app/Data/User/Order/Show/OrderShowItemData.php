<?php

namespace App\Data\User\Order\Show;

use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'userShowOrderDetails')]
class OrderShowItemData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer'),
        ]
        public int $id,
        #[OAT\Property(
            type: 'integer',
        )]
        public int $quantity,
        #[
            OAT\Property(type: 'number'),
        ]
        public float $price,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'string'),
            WithTransformer(ToWebStoragePathTransformer::class, folder: 'product')
        ]
        public ?string $image,
    ) {
    }
}
