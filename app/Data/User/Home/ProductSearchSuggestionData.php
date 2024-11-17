<?php

namespace App\Data\User\Home;

use App\Models\Product;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class ProductSearchSuggestionData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public ?string $image_url,
    ) {
    }

    public static function fromModel(Product $product): self
    {
        // Log::info('product {product}', ['product' => $product]);

        $product_variants_count =
            $product
                ->variants
                ->count();

        //i.e small/red/neon or small/blue/mat
        if ($product_variants_count == 3) {
            $product_second_variant_combinations =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere('is_thumb', true);

            return new self(
                id: $product_second_variant_combinations->id,
                name: $product->name,
                image_url: $product_second_variant_combinations
                    ->medially
                    ->first()
                    ->file_url
            );
        }

        //i.e small/red
        if ($product_variants_count == 2) {
            $product_variant_combinations =
                $product
                    ->variants
                    ->pluck('variantValues')// it pastes from 'variantValues' => $value, the $value part to an array -> [[['id' => 25, name => 'variantvalue'],['id' => 20, name => 'otherVariant']]]
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere('is_thumb', true);

            return new self(
                id: $product_variant_combinations->id,
                name: $product->name,
                image_url: $product_variant_combinations
                    ->medially
                    ->first()
                    ->file_url
            );
        }

        //i.e small, medium etc.
        if ($product_variants_count == 1) {
            $product_variant_combinations =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->firstWhere('is_thumb', true);

            return new self(
                id: $product_variant_combinations->id,
                name: $product->name,
                image_url: $product_variant_combinations
                    ->medially()
                    ->first()
                    ->file_url
            );
        }

        return new self(
            id: $product->id,
            name: $product->name,
            image_url: $product
                ->medially()
                ->first()
                ->file_url
        );

    }
}
