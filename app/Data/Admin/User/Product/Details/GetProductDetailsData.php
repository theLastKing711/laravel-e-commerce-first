<?php

namespace App\Data\Admin\User\Product\Details;

use App\Data\Admin\User\Product\Variant\VariantData;
use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Product;
use App\Models\VariantValue;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class GetProductDetailsData extends Data
{
    public function __construct(
        #[OAT\Property]
        public string $id,
        #[OAT\Property]
        public ?ProductVariationData $variation,
        #[OAT\Property]
        public string $name,
        #[OAT\Property]
        public string $price,
        #[OAT\Property]
        public ?bool $is_favourite,
        #[OAT\Property]
        public SingleMedia $image,
        #[ArrayProperty(VariantData::class)]
        /** @var Collection<int, VariantData> */
        public Collection $variants,
    ) {
    }

    //code be named anything other than fromMultiple
    public static function fromMultiple(Product $product, int $product_variation_id): self
    {
        $product_variants_count =
        $product
            ->variants
            ->count();

        Debugbar::info($product
            ->variants
            ->count());

        //i.e small/red/neon or small/blue/mat
        if ($product_variants_count == 3) {

            /** @var VariantValue $product_second_variant_combination */
            $product_second_variant_combination =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere('id', $product_variation_id);

            Debugbar::info(
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
            );

            $product_variation = ProductVariationData::from([
                'id' => $product_second_variant_combination->id,
                'price' => $product_second_variant_combination->price,
                'image' => SingleMedia::from($product),
            ]);

            $product_variants = VariantData::collect($product->variants);

            return new self(
                id: $product->id,
                variation: $product_variation,
                name: $product->name,
                price: $product->price,
                is_favourite: $product->is_favourite,
                image: SingleMedia::from($product),
                variants: $product_variants
            );
        }

        //i.e small/red
        if ($product_variants_count == 2) {

            /** @var VariantValue $product_variant_combination */
            $product_variant_combination =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere('id', $product_variation_id);

            $product_variation = ProductVariationData::from([
                'id' => $product_variant_combination->id,
                'price' => $product_variant_combination->price,
                'image' => SingleMedia::from($product_variant_combination),

            ]);

            $product_variants = VariantData::collect($product->variants);

            return new self(
                id: $product->id,
                variation: $product_variation,
                name: $product->name,
                price: $product->price,
                is_favourite: $product->is_favourite,
                image: SingleMedia::from($product),
                variants: $product_variants
            );
        }

        //i.e small, medium etc.
        if ($product_variants_count == 1) {

            /** @var VariantValue $product_variant_value */
            $product_variant_value =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->firstWhere('id', $product_variation_id);

            $product_variation = ProductVariationData::from([
                'id' => $product_variant_value->id,
                'price' => $product_variant_value->price,
                'image' => SingleMedia::from($product_variant_value),

            ]);

            $product_variants = VariantData::collect($product->variants);

            return new self(
                id: $product->id,
                variation: $product_variation,
                name: $product->name,
                price: $product->price,
                is_favourite: $product->is_favourite,
                image: SingleMedia::from($product),
                variants: $product_variants
            );
        }

        return new self(
            id: $product->id,
            variation: null,
            name: $product->name,
            price: $product->price,
            is_favourite: $product->is_favourite,
            image: SingleMedia::from($product),
            variants: collect([]),
        );
    }
}
