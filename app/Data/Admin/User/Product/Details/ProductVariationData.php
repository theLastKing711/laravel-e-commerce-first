<?php

namespace App\Data\Admin\User\Product\Details;

use App\Data\Shared\Media\SingleMedia;
use App\Models\Product;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class ProductVariationData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $id,
        // #[OAT\Property]
        // public string $name,
        #[OAT\Property]
        public string $price,
        #[OAT\Property]
        public SingleMedia $image,
    ) {
    }

    // public static function fromModel(Product $product): self
    // {

    // }
}
