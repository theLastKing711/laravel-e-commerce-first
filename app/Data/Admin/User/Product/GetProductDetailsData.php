<?php

namespace App\Data\Admin\User\Product;

use App\Models\Product;
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
        public string $image_url,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        Log::info('product {product}', ['product' => $product]);

        $image_url = $product->medially()->first()->file_url;

        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            is_favourite: $product->is_favourite,
            image_url: $image_url
        );
    }
}
