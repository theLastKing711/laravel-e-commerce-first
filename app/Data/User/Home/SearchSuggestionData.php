<?php

namespace App\Data\User\Home;

use App\Models\Product;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class SearchSuggestionData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public ?string $image_url,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        // Log::info('product {product}', ['product' => $product]);

        $variants_count = $product->variants()->count();

        if ($variants_count == 0) {

            return new self(
                id: $product->id,
                name: $product->name,
                image_url: $product->medially()->first()->file_url
            );
        }

        // image and id is picked from main(is_main) item in variant_values
        //i.e small, medium etc.
        if ($variants_count == 1) {
            $product_variant = $product
                ->variants
                ->pluck('variantValues')
                ->flatten()
                ->first();

            return new self(
                id: $product_variant->id,
                name: $product->name,
                image_url: $product_variant->medially()->first()->file_url
            );
        }

        //image and id is picked from main(is_main) item in variant_combinataions
        //i.e small/red
        if ($variants_count == 2) {
            $product_variant = $product
                ->variants
                ->pluck('variantValues')// it pastes from 'variantValues' => $value, the $value part to an array -> [[['id' => 25, name => 'variantvalue'],['id' => 20, name => 'otherVariant']]]
                ->flatten()
                ->pluck('combinations')
                ->flatten()
                ->first();

            return new self(
                id: $product_variant->id,
                name: $product->name,
                image_url: $product_variant->medially()->first()->file_url
            );
        }

        //image and id is picked from main(is_main) item in second_variant_combinataions
        //i.e small/red/neon or small/blue/mat
        if ($variants_count == 3) {
            $product_variant = $product
                ->variants
                ->pluck('variantValues')
                ->flatten()
                ->pluck('combinations')
                ->flatten()
                ->pluck('combinations')
                ->flatten()
                ->first();

            return new self(
                id: $product_variant->id,
                name: $product->name,
                image_url: $product_variant->medially()->first()->file_url
            );
        }
    }
}
