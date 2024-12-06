<?php

namespace App\Data\User\Home;

use App\Data\Admin\User\Product\Details\QueryParameters\GetProductDetailsQueryParameterData;
use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\PivotContainer;
use App\Models\Product;
use App\Models\SecondVariantCombination;
use App\Models\Variant;
use App\Models\VariantCombination;
use App\Models\VariantValue;
use Barryvdh\Debugbar\Facades\Debugbar;
use Log;
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
        public ?ProductVariationSearchData $product_variation = null,
        public ?GetProductDetailsQueryParameterData $variant_value_ids_query_parameters = null
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

            /** @var VariantValue&PivotContainer<SecondVariantCombination> $product_thumb_third_variant_value_with_second_variant_combination */
            $product_thumb_third_variant_value_with_second_variant_combination =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->pluck('pivot')
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere('pivot.is_thumb', true);

            $product_thumb_second_variant_combination =
                $product_thumb_third_variant_value_with_second_variant_combination
                    ->pivot;

            // Debugbar::info($product_thumb_second_variant_combination)

            $product_variation = new ProductVariationSearchData(
                id: $product_thumb_second_variant_combination
                    ->id,
                available: $product_thumb_second_variant_combination
                        ->available,
                price: $product_thumb_second_variant_combination
                        ->price,
                image: SingleMedia::fromModel($product_thumb_second_variant_combination)
            );

            $product_thumb_variant_combination =
                $product_thumb_second_variant_combination
                    ->variantCombination;

            $product_thumb_second_variant_value_id =
                $product_thumb_variant_combination
                    ->first_variant_value
                    ->id;

            $product_thumb_first_variant_value_id =
                $product_thumb_variant_combination
                    ->second_variant_value_id;

            $product_thumb_third_variant_value_id =
                $product_thumb_third_variant_value_with_second_variant_combination
                    ->id;

            $variant_value_ids_query_parameters =
                new GetProductDetailsQueryParameterData(
                    first_variant_value_id: $product_thumb_first_variant_value_id,
                    second_variant_value_id: $product_thumb_second_variant_value_id,
                    third_variant_value_id: $product_thumb_third_variant_value_id
                );

            return new self(
                id: $product->id,
                name: $product->name,
                image_url: $product
                    ->medially
                    ->first()
                    ?->file_url,
                product_variation: $product_variation,
                variant_value_ids_query_parameters: $variant_value_ids_query_parameters
            );
        }

        //i.e small/red
        if ($product_variants_count == 2) {

            /** @var VariantValue&PivotContainer<VariantCombination> $product_thumb_first_variant_value_with_variant_combination product's first variant combination that is thumb */
            $product_thumb_first_variant_value_with_variant_combination =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere('pivot.is_thumb', true);

            $product_thumb_variant_combination =
                $product_thumb_first_variant_value_with_variant_combination
                    ->pivot;

            Log::info($product_thumb_first_variant_value_with_variant_combination);

            $product_variation = new ProductVariationSearchData(
                id: $product_thumb_variant_combination
                        ->id,
                available: $product_thumb_variant_combination
                            ->available,
                price: $product_thumb_variant_combination
                            ->price,
                image: SingleMedia::fromModel($product_thumb_variant_combination)
            );

            $product_thumb_second_variant_value =
                $product_thumb_variant_combination
                    ->first_variant_value;

            return new self(
                id: $product->id,
                name: $product->name,
                image_url: $product
                    ->medially
                    ->first()
                    ?->file_url,
                product_variation: $product_variation,
                variant_value_ids_query_parameters: new GetProductDetailsQueryParameterData(
                    first_variant_value_id: $product_thumb_first_variant_value_with_variant_combination
                            ->id,
                    second_variant_value_id: $product_thumb_second_variant_value
                        ->id,
                    third_variant_value_id: null
                )
            );
        }

        //i.e small, medium etc.
        if ($product_variants_count == 1) {

            /** @var VariantValue|null $product_thumb_variant_value produt's first variant value that is thumb */
            $product_thumb_variant_value =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->firstWhere('is_thumb', true);

            $product_variation = new ProductVariationSearchData(
                id: $product_thumb_variant_value
                    ->id,
                available: $product_thumb_variant_value
                        ->available,
                price: $product_thumb_variant_value
                        ->price,
                image: SingleMedia::fromModel($product_thumb_variant_value)
            );

            return new self(
                id: $product->id,
                name: $product->name,
                image_url: null,
                product_variation: $product_variation,
                variant_value_ids_query_parameters: new GetProductDetailsQueryParameterData(
                    first_variant_value_id: $product_thumb_variant_value->id,
                    second_variant_value_id: null,
                    third_variant_value_id: null
                )
            );
        }

        return new self(
            id: $product->id,
            name: $product->name,
            image_url: $product
                ->medially
                ->first()
                ?->file_url,
        );

    }
}
