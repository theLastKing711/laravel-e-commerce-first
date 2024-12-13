<?php

namespace App\Data\User\Order\Show;

use App\Data\Shared\Media\SingleMedia;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class OrderShowItemData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $product_id,
        #[OAT\Property]
        public ?string $product_variation_id,
        #[OAT\Property()]
        public int $quantity,
        #[OAT\Property()]
        public float $price,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public ?SingleMedia $image,
    ) {}
}

// WithTransformer(ToWebStoragePathTransformer::class, folder: 'product')
