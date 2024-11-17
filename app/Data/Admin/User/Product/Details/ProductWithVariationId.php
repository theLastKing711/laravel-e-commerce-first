<?php

namespace App\Data\Admin\User\Product\Details;

use App\Models\Product;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class ProductWithVariationId extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $variation_id,
        #[OAT\Property]
        public Product $product
    ) {
    }

    // public static function fromModel(Product $product): self
    // {

    // }
}
