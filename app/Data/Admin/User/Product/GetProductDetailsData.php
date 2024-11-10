<?php

namespace App\Data\Admin\User\Product;

use App\Data\Admin\User\Product\Variant\VariantData;
use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Product;
use Illuminate\Support\Collection;
use Log;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class GetProductDetailsData extends Data
{
    public function __construct(
        #[OAT\Property]
        public int $id,
        #[OAT\Property]
        public string $name,
        #[OAT\Property]
        public string $price,
        #[OAT\Property]
        public bool $is_favourite,
        #[OAT\Property]
        public SingleMedia $image,
        #[ArrayProperty(VariantData::class)]
        /** @var Collection<int, VariantData> */
        public Collection $variants,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        // Log::info('product {product}', ['product' => $product]);

        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            is_favourite: $product->is_favourite,
            image: SingleMedia::from($product),
            variants: VariantData::collect($product->variants, Collection::class),
        );
    }
}
