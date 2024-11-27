<?php

namespace App\Data\Admin\User\Product\Details;

use App\Data\Admin\User\Product\Details\Variant\VariantValueData\VariantValueData;
use App\Data\Admin\User\Product\Variant\VariantData;
use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantCombination;
use App\Models\VariantValue;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;

#[Oat\Schema()]
class GetProductDetailsData
{
    /** @param Collection<int, VariantData> $variants */
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

            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant, int $variant_index) use ($variant_value_ids_query_parameter) {

                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function ($variantValue) use ($variant_index, $variant_value_ids_query_parameter) {

                                    $is_product_first_variant = $variant_index == 0;

                                    if ($is_product_first_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->first_variant_value_id == $variantValue->id;

                                        $first_variant_query_parameter_variant_value_id
                                            = $variantValue->id;

                                        $current_variant_variant_value_has_combination_with_selected_second_and_third =
                                        (bool) $variantValue
                                            ->combinations
                                            ->merge($variantValue->combined_by)
                                            ->contains(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                $second_variant_value_has_combination_with_current_variant_value =
                                                    $variant_value_with_pivot_variant_combination
                                                        ->id
                                                    ==
                                                    $variant_value_ids_query_parameter
                                                        ->second_variant_value_id;

                                                if ($second_variant_value_has_combination_with_current_variant_value) {
                                                    $current_variant_value_and_second_has_combination_with_third =
                                                        $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->contains(function ($variant_value_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {
                                                                return
                                                                    $variant_value_with_pivot_second_variant_combination
                                                                        ->pivot
                                                                        ->variant_value_id
                                                                    ==
                                                                    $variant_value_ids_query_parameter
                                                                        ->third_variant_value_id;
                                                            });

                                                    return $current_variant_value_and_second_has_combination_with_third;

                                                }

                                                return false;

                                            });

                                        if ($current_variant_variant_value_has_combination_with_selected_second_and_third) {

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: false,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                    second_variant_value_id: $variant_value_ids_query_parameter
                                                                                ->second_variant_value_id,
                                                    third_variant_value_id: null,
                                                )
                                            );
                                        }

                                        $current_variant_variant_value_first_combination_with_second_and_third =
                                            $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->first(function ($variant_value_with_pivot_variant_combination) {

                                                    return
                                                        ! $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->ieEmpty();

                                                });

                                        $current_variant_variant_value_has_any_combination_with_second_and_third =
                                        (bool) $current_variant_variant_value_first_combination_with_second_and_third;

                                        if ($current_variant_variant_value_has_any_combination_with_second_and_third) {

                                            $second_vairant_value_id =
                                                $current_variant_variant_value_first_combination_with_second_and_third
                                                    ->variant_id;

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: true,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                    second_variant_value_id: $second_vairant_value_id,
                                                    third_variant_value_id: null,
                                                )
                                            );
                                        }

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_current_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                second_variant_value_id: null,
                                                third_variant_value_id: null,
                                            )
                                        );

                                    }

                                    $is_product_second_variant = $variant_index == 1;

                                    if ($is_product_second_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->second_variant_value_id
                                                ==
                                                $variantValue
                                                    ->id;

                                        $second_variant_query_parameter_variant_value_ =
                                            $variantValue
                                                ->id;

                                        $current_variant_variant_value_has_combination_with_selected_first_and_third =
                                            $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->contains(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                    $second_variant_value_has_combination_with_first_variant_value =
                                                        $variant_value_ids_query_parameter
                                                            ->first_variant_value_id
                                                        ==
                                                        $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->first_variant_value_id;

                                                    if ($second_variant_value_has_combination_with_first_variant_value) {
                                                        return $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->contains(function ($variant_value_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {
                                                                $first_and_second_variant_values_has_combination_with_third_variant_value =
                                                                    $variant_value_with_pivot_second_variant_combination
                                                                        ->pivot
                                                                        ->variant_value_id
                                                                        ==
                                                                    $variant_value_ids_query_parameter
                                                                        ->third_variant_value_id;

                                                                return $first_and_second_variant_values_has_combination_with_third_variant_value;
                                                            });

                                                    }

                                                    return false;

                                                });

                                        if ($current_variant_variant_value_has_combination_with_selected_first_and_third) {

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: false,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $variant_value_ids_query_parameter
                                                        ->first_variant_value_id,
                                                    second_variant_value_id: $variant_value_ids_query_parameter
                                                                                ->second_variant_value_id,
                                                    third_variant_value_id: $variant_value_ids_query_parameter
                                                                                ->third_variant_value_id
                                                )
                                            );
                                        }

                                        $current_variant_variant_value_first_combination_with_first_and_third =
                                            $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->first(function ($variant_value_with_pivot_variant_combination) {

                                                    $variant_combination_has_combination =
                                                        ! $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->isEmpty();

                                                    return $variant_combination_has_combination;

                                                });

                                        $current_variant_variant_value_has_any_combination_with_first_and_third =
                                            (bool) $current_variant_variant_value_first_combination_with_first_and_third;

                                        if ($current_variant_variant_value_has_any_combination_with_first_and_third) {

                                            $first_vairant_value_id =
                                                $current_variant_variant_value_first_combination_with_first_and_third
                                                    ->pivot
                                                    ->second_variant_value_id;

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: true,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_vairant_value_id,
                                                    second_variant_value_id: $variant_value_ids_query_parameter
                                                                                ->second_variant_value_id,
                                                    third_variant_value_id: $current_variant_variant_value_first_combination_with_first_and_third
                                                        ->pivot
                                                        ->combinations
                                                        ->first()
                                                        ->pivot
                                                        ->variant_value_id,
                                                )
                                            );
                                        }

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_current_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: null,
                                                second_variant_value_id: $variant_value_ids_query_parameter
                                                                            ->second_variant_value_id,
                                                third_variant_value_id: null,
                                            )
                                        );
                                    }

                                    $is_product_third_variant = $variant_index == 2;

                                    if ($is_product_third_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->third_variant_value_id
                                            ==
                                            $variantValue
                                                ->id;

                                        $third_variant_query_parameter_variant_value_id =
                                            $variantValue
                                                ->id;

                                        $third_variant_variant_value_first_combination_with_selected_first_and_second =
                                            $variantValue
                                                ->late_combinations
                                                ->first(function (VariantCombination $variant_combination_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {

                                                    $variant_combination_has_combination_with_third_variant_value =
                                                        (
                                                            $variant_value_ids_query_parameter
                                                                ->first_variant_value_id
                                                            ==
                                                            $variant_combination_with_pivot_second_variant_combination
                                                                ->second_variant_value_id
                                                            &&
                                                            $variant_value_ids_query_parameter
                                                                ->second_variant_value_id
                                                            ==
                                                            $variant_combination_with_pivot_second_variant_combination
                                                                ->first_variant_value_id
                                                        );

                                                    return $variant_combination_has_combination_with_third_variant_value;

                                                });

                                        $third_variant_variant_value_has_combination_with_selected_first_and_second =
                                            (bool) $third_variant_variant_value_first_combination_with_selected_first_and_second;

                                        if ($third_variant_variant_value_has_combination_with_selected_first_and_second) {
                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: false,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $third_variant_variant_value_first_combination_with_selected_first_and_second
                                                            ->second_variant_value_id,
                                                    second_variant_value_id: $third_variant_variant_value_first_combination_with_selected_first_and_second
                                                            ->first_variant_value_id,
                                                    third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                                )
                                            );
                                        }

                                        $third_variant_variant_value_first_combination_with_first_and_second =
                                            $variantValue
                                                ->late_combinations
                                                ->first();

                                        $third_variant_variant_value_has_any_combination_with_first_and_second =
                                            (bool) $third_variant_variant_value_first_combination_with_first_and_second;

                                        if ($third_variant_variant_value_has_any_combination_with_first_and_second) {
                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: true,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $third_variant_variant_value_first_combination_with_first_and_second
                                                            ->second_variant_value_id,
                                                    second_variant_value_id: $third_variant_variant_value_first_combination_with_selected_first_and_second
                                                            ->first_variant_value_id,
                                                    third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                                )
                                            );
                                        }

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_current_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: null,
                                                second_variant_value_id: null,
                                                third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                            )
                                        );

                                    }

                                });

                        return new VariantData(
                            id: $variant->id,
                            name: $variant->name,
                            variant_values: $variant_values_data
                        );
                    });

            $all_query_paramaeter_variant_values_are_available =
                $variant_value_ids_query_parameter
                    ->first_variant_value_id
                &&
                $variant_value_ids_query_parameter
                    ->second_variant_value_id
                &&
                $variant_value_ids_query_parameter
                    ->third_variant_value_id;

            if ($all_query_paramaeter_variant_values_are_available) {

                $selected_first_variant_value =
                    $product
                        ->variants
                        ->first()
                        ->variantValues
                        ->firstWhere(
                            'id',
                            $variant_value_ids_query_parameter
                                ->first_variant_value_id
                        );

                $selected_variant_combination =
                    $selected_first_variant_value
                        ->combinations
                        ->merge($selected_first_variant_value->combined_by)
                        ->first(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                            $first_vairant_value_combination =
                                $variant_value_with_pivot_variant_combination
                                    ->id
                                ==
                                $variant_value_ids_query_parameter
                                    ->second_variant_value_id;

                            return $first_vairant_value_combination;

                        });

                $selected_second_variant_combination =
                    $selected_variant_combination
                        ->pivot
                        ->combinations
                        ->first(function ($variant_combination_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {

                            $variant_combination_combination =
                                $variant_combination_with_pivot_second_variant_combination
                                    ->pivot
                                    ->id
                                ==
                                $variant_value_ids_query_parameter
                                    ->third_variant_value_id;

                            return $variant_combination_combination;
                        })
                        ->pivot;

                $product_variation = ProductVariationData::from([
                    'id' => $selected_second_variant_combination->id,
                    'price' => $selected_second_variant_combination->price,
                    'image' => SingleMedia::from($selected_second_variant_combination),

                ]);

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

            return new self(
                id: $product->id,
                variation: null,
                name: $product->name,
                price: $product->price,
                is_favourite: $product->is_favourite,
                image: SingleMedia::fromModel($product),
                variants: $product_variants_data
            );
        }

        //i.e small/red
        if ($product_variants_count == 2) {

            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant, int $variant_index) use ($variant_value_ids_query_parameter) {

                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function (VariantValue $variantValue) use ($variant_index, $variant_value_ids_query_parameter) {

                                    $is_product_first_variant = $variant_index == 0;

                                    if ($is_product_first_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->first_variant_value_id
                                                ==
                                            $variantValue->id;

                                        $first_variant_query_parameter_variant_value_id
                                            = $variantValue->id;

                                        $current_variant_variant_value_has_combination_with_selected_second =
                                        (bool) $variantValue
                                            ->combinations
                                            ->merge($variantValue->combined_by)
                                            ->first(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                $current_variant_variant_value_has_combination_with_selected_second_variant_value =
                                                    $variant_value_with_pivot_variant_combination
                                                        ->variant_id
                                                        ==
                                                    $variant_value_ids_query_parameter
                                                        ->second_variant_value_id;

                                                return $current_variant_variant_value_has_combination_with_selected_second_variant_value;

                                            });

                                        if ($current_variant_variant_value_has_combination_with_selected_second) {

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: false,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                    second_variant_value_id: $variant_value_ids_query_parameter
                                                                                ->second_variant_value_id,
                                                    third_variant_value_id: null
                                                )
                                            );
                                        }

                                        $current_variant_variant_value_first_combination_with_second
                                            = $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->first();

                                        $current_variant_variant_value_has_any_combination_with_second =
                                        (bool) $current_variant_variant_value_first_combination_with_second;

                                        if ($current_variant_variant_value_has_any_combination_with_second) {

                                            $second_vairant_value_id =
                                                $current_variant_variant_value_first_combination_with_second
                                                    ->variant_id;

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: true,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                    second_variant_value_id: $second_vairant_value_id,
                                                    third_variant_value_id: null,
                                                )
                                            );
                                        }

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_current_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                second_variant_value_id: null,
                                                third_variant_value_id: null,
                                            )
                                        );

                                    }

                                    $is_product_second_variant = $variant_index == 1;

                                    if ($is_product_second_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->second_variant_value_id
                                                ==
                                                $variantValue->id;

                                        $second_variant_query_parameter_variant_value_id =
                                            $variantValue->id;

                                        $current_variant_variant_value_has_combination_with_selected_first =
                                        (bool) $variantValue
                                            ->combinations
                                            ->merge($variantValue->combined_by)
                                            ->first(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                $current_variant_variant_value_has_combination_with_selected_first_variant_value =
                                                    $variant_value_with_pivot_variant_combination
                                                        ->variant_id
                                                        ==
                                                    $variant_value_ids_query_parameter
                                                        ->first_variant_value_id;

                                                return $current_variant_variant_value_has_combination_with_selected_first_variant_value;

                                            });

                                        if ($current_variant_variant_value_has_combination_with_selected_first) {

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: false,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $variant_value_ids_query_parameter
                                                                                ->first_variant_value_id,
                                                    second_variant_value_id: $second_variant_query_parameter_variant_value_id,
                                                    third_variant_value_id: null
                                                )
                                            );
                                        }

                                        $current_variant_variant_value_first_combination_with_first =
                                            $variantValue
                                                ->combinations
                                                ->merge($variantValue->combined_by)
                                                ->first();

                                        $current_variant_variant_value_has_any_combination_with_first =
                                        (bool) $current_variant_variant_value_first_combination_with_first;

                                        if ($current_variant_variant_value_has_any_combination_with_first) {

                                            $first_vairant_value_id =
                                                $current_variant_variant_value_first_combination_with_first
                                                    ->variant_id;

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: true,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_vairant_value_id,
                                                    second_variant_value_id: $second_variant_query_parameter_variant_value_id,
                                                    third_variant_value_id: null,
                                                )
                                            );
                                        }

                                        return new VariantValueData(
                                            id: $variantValue->id,
                                            name: $variantValue->name,
                                            is_selected: $is_current_variant_value_selected,
                                            is_not_available: true,
                                            image: SingleMedia::fromModel($variantValue),
                                            combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                first_variant_value_id: null,
                                                second_variant_value_id: $second_variant_query_parameter_variant_value_id,
                                                third_variant_value_id: null,
                                            )
                                        );

                                    }

                                });

                        return new VariantData(
                            id: $variant->id,
                            name: $variant->name,
                            variant_values: $variant_values_data
                        );
                    });

            $all_query_paramaeter_variant_values_are_available =
                $variant_value_ids_query_parameter
                    ->first_variant_value_id
                &&
                $variant_value_ids_query_parameter
                    ->second_variant_value_id;

            if ($all_query_paramaeter_variant_values_are_available) {

                $selected_first_variant_value =
                    $product
                        ->variants
                        ->first()
                        ->variantValues
                        ->firstWhere(
                            'id',
                            $variant_value_ids_query_parameter
                                ->first_variant_value_id
                        );

                $selected_variant_combination =
                    $selected_first_variant_value
                        ->combinations
                        ->merge($selected_first_variant_value->combined_by)
                        ->first(function ($variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                            $first_vairant_value_combination =
                                $variant_value_with_pivot_variant_combination
                                    ->id
                                ==
                                $variant_value_ids_query_parameter
                                    ->second_variant_value_id;

                            return $first_vairant_value_combination;

                        });

                $product_variation = ProductVariationData::from([
                    'id' => $selected_variant_combination->pivot->id,
                    'price' => $selected_variant_combination->pivot->price,
                    'image' => SingleMedia::from($selected_variant_combination->pivot),

                ]);

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

            return new self(
                id: $product->id,
                variation: null,
                name: $product->name,
                price: $product->price,
                is_favourite: $product->is_favourite,
                image: SingleMedia::from($product),
                variants: $product_variants_data
            );
        }

        //i.e small, medium etc.
        if ($product_variants_count == 1) {

            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant, int $variant_index) use ($variant_value_ids_query_parameter) {

                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function ($variantValue) use ($variant_value_ids_query_parameter) {

                                    $is_current_variant_value_selected =
                                        $variant_value_ids_query_parameter
                                            ->first_variant_value_id
                                        ==
                                        $variantValue
                                            ->id;

                                    return new VariantValueData(
                                        id: $variantValue->id,
                                        name: $variantValue->name,
                                        is_selected: $is_current_variant_value_selected,
                                        is_not_available: false,
                                        image: SingleMedia::fromModel($variantValue),
                                        combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                            first_variant_value_id: $variantValue->id,
                                            second_variant_value_id: null,
                                            third_variant_value_id: null,
                                        )
                                    );
                                });

                        return new VariantData(
                            id: $variant->id,
                            name: $variant->name,
                            variant_values: $variant_values_data
                        );

                    });

            $seleted_variant_value =
                $product
                    ->variants
                    ->first()
                    ->variantValues
                    ->firstWhere(
                        'id',
                        $variant_value_ids_query_parameter
                            ->first_variant_value_id
                    );

            $product_variation = ProductVariationData::from([
                'id' => $seleted_variant_value->id,
                'price' => $seleted_variant_value->price,
                'image' => SingleMedia::from($seleted_variant_value),

            ]);
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
