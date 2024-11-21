<?php

namespace App\Data\Admin\User\Product\Details;

use App\Data\Admin\User\Product\Details\Variant\VariantValueData\VariantValueData;
use App\Data\Admin\User\Product\Variant\VariantData;
use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantValue;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
    //used with from(option1, option2), not from([]) form;
    public static function fromMultiple(
        Product $product,
        GetProductDetailsQueryParameterData $variant_value_ids_query_parameter
    ): self {
        $product_variants_count =
            $product
                ->variants
                ->count();

        Debugbar::info($product
            ->variants
            ->count());

        //i.e small/red/neon or small/blue/mat
        if ($product_variants_count == 3) {

            /** @var VariantValue|null $product_second_variant_combination */
            $product_second_variant_combination =
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
                    ->firstWhere(
                        'variant_value_id',
                        $variant_value_ids_query_parameter
                            ->third_variant_value_id
                    );

            Debugbar::info(
                $product
                    ->variants
                    ->pluck('variantValues')
                    ->flatten()
                    ->pluck('combinations')
                    ->flatten()
            );

            $product_variation =
            $product_second_variant_combination ?
             new ProductVariationData(
                 id: $product_second_variant_combination->id,
                 available: $product_second_variant_combination->available,
                 price: $product_second_variant_combination->price,
                 image: SingleMedia::fromModel($product),
             )
            :
            null;

            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant, int $variant_index) use ($variant_value_ids_query_parameter) {

                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function (VariantValue $variantValue) use ($variant_index, $variant_value_ids_query_parameter) {

                                    $is_product_first_variant = $variant_index == 0;

                                    $is_first_variant_variant_value_selected =
                                        $is_product_first_variant
                                        &&
                                        $variant_value_ids_query_parameter
                                            ->first_variant_value_id == $variantValue->id;

                                    $is_product_second_variant = $variant_index == 1;

                                    $is_second_variant_variant_value_selected =
                                        $is_product_second_variant
                                        &&
                                        $variant_value_ids_query_parameter
                                            ->second_variant_value_id == $variantValue->id;

                                    $is_product_third_variant = $variant_index == 2;

                                    $is_third_variant_variant_value_selected =
                                        $is_product_third_variant
                                        &&
                                        $variant_value_ids_query_parameter
                                            ->third_variant_value_id == $variantValue->id;

                                    $is_variant_value_selected =
                                        $is_first_variant_variant_value_selected
                                        ||
                                        $is_second_variant_variant_value_selected
                                        ||
                                        $is_third_variant_variant_value_selected;

                                    if ($is_product_first_variant) {

                                        $first_variant_query_parameter_variant_value_id
                                            = $variantValue->id;

                                        $first_variant_variant_value_combines_with_second_and_third =
                                            $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->contains(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                    if (
                                                        $variant_value_ids_query_parameter
                                                            ->first_variant_value_id
                                                        ==
                                                        $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->$first_variant_value_id
                                                        ||
                                                        $variant_value_ids_query_parameter
                                                            ->second_variant_value_id
                                                        ==
                                                        $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->$first_variant_value_id
                                                    ) {
                                                        return $variant_value_with_pivot_variant_combination
                                                            ->combinations
                                                            ->contains(function ($variant_value_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {
                                                                $first_and_second_variant_values_combine_with_second_and_third_variant_values =
                                                                    $variant_value_with_pivot_second_variant_combination
                                                                        ->pivot
                                                                        ->variant_value_id
                                                                        ==
                                                                        $variant_value_ids_query_parameter
                                                                            ->third_variant_value_id;

                                                            });
                                                    }

                                                    return false;

                                                });

                                        // $second_variant_query_parameter_variant_value_id =
                                        //     $variantValue
                                        //         ->combinations
                                        //         ->merge($variantValue->combined_by)
                                        //         ->firstWhere(
                                        //             function (Builder $query) use ($variant_value_ids_query_parameter) {
                                        //                 $query
                                        //                     ->where(
                                        //                         'pivot.first_variant_value_id',
                                        //                         $variant_value_ids_query_parameter
                                        //                             ->second_variant_value_id
                                        //                     )
                                        //                     ->orWhere(
                                        //                         'pivot.second_variant_value_id',
                                        //                         $variant_value_ids_query_parameter
                                        //                             ->second_variant_value_id
                                        //                     );
                                        //             }
                                        //         )
                                        //         ?
                                        //         $variant_value_ids_query_parameter
                                        //             ->second_variant_value_id
                                        //         :
                                        //         null;

                                        // $third_variant_query_parameter_variant_value_id =
                                        //     $variantValue
                                        //         ->combinations
                                        //         ->merge($variantValue->combined_by)
                                        //         ->selectMany('combinations')
                                        //         ->flatten()
                                        //         ->firstWhere(
                                        //             'pivot.variant_value_id',
                                        //             $variant_value_ids_query_parameter
                                        //                 ->third_variant_value_id
                                        //         )
                                        //         ?
                                        //         $variant_value_ids_query_parameter
                                        //             ->third_variant_value_id
                                        //         :
                                        //         null;

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            variant_value_ids_query_parameter: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                second_variant_value_id: $second_variant_query_parameter_variant_value_id,
                                                third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                            )
                                        );
                                    }

                                    if ($is_product_second_variant) {

                                        $first_variant_query_parameter_variant_value_id =
                                        $variantValue
                                            ->combinations
                                            ->merge($variantValue->combined_by)
                                            ->firstWhere(
                                                function (Builder $query) use ($variant_value_ids_query_parameter) {
                                                    $query
                                                        ->where(
                                                            'pivot.first_variant_value_id',
                                                            $variant_value_ids_query_parameter
                                                                ->first_variant_value_id
                                                        )
                                                        ->orWhere(
                                                            'pivot.second_variant_value_id',
                                                            $variant_value_ids_query_parameter
                                                                ->first_variant_value_id
                                                        );
                                                }
                                            )
                                            ?
                                            $variant_value_ids_query_parameter->second_variant_value_id
                                            :
                                            null;

                                        $second_variant_query_parameter_variant_value_id =
                                            $variantValue->id;

                                        $third_variant_query_parameter_variant_value_id =
                                            $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->selectMany('combinations')
                                                ->flatten()
                                                ->firstWhere(
                                                    'pivot.variant_value_id',
                                                    $variant_value_ids_query_parameter
                                                        ->third_variant_value_id
                                                )
                                                ?
                                                $variant_value_ids_query_parameter
                                                    ->third_variant_value_id
                                                :
                                                null;

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            variant_value_ids_query_parameter: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                second_variant_value_id: $second_variant_query_parameter_variant_value_id,
                                                third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                            )
                                        );
                                    }

                                    if ($is_product_third_variant) {

                                        $third_variant_query_parameter_variant_value_id =
                                            $variantValue->id;

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            variant_value_ids_query_parameter: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                second_variant_value_id: $second_variant_query_parameter_variant_value_id,
                                                third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                            )
                                        );
                                    }

                                    return new VariantValueData(
                                        id: $variantValue->id,
                                        name: $variantValue->name,
                                        is_selected: $is_variant_value_selected,
                                        image: SingleMedia::fromModel($variantValue)
                                    );
                                });

                        return new VariantData(
                            id: $variant->id,
                            name: $variant->name,
                            variant_values: $variant_values_data
                        );
                    });

            return new self(
                id: $product->id,
                variation: $product_variation,
                name: $product->name,
                price: $product->price,
                is_favourite: $product->is_favourite,
                image: SingleMedia::fromModel($product),
                variants: $product_variants_data
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
                    ->firstWhere(
                        'id',
                        $variant_value_ids_query_parameter
                            ->second_variant_value_id
                    );

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
                    ->firstWhere(
                        'id',
                        $variant_value_ids_query_parameter
                            ->first_variant_value_id
                    );

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
