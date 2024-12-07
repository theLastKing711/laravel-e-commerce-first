<?php

namespace App\Data\Admin\User\Product\Details;

use App\Data\Admin\User\Product\Details\QueryParameters\GetProductDetailsQueryParameterData;
use App\Data\Admin\User\Product\Details\Variant\VariantData;
use App\Data\Admin\User\Product\Details\Variant\VariantValue\VariantValueData;
use App\Data\Shared\Media\SingleMedia;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantCombination;
use App\Models\VariantValue;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class GetProductDetailsData extends Data
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
        public ?SingleMedia $image,
        #[ArrayProperty(VariantData::class)]
        public Collection $variants,
    ) {}

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

        Debugbar::info(
            $product
                ->variants
                ->count()
        );

        //i.e small/red/neon or small/blue/mat
        if ($product_variants_count == 3) {

            Debugbar::info('product with three variants');

            /** @var Collection<int, VariantData> $product_variants_data */
            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant, int $variant_index) use ($variant_value_ids_query_parameter): VariantData {
                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function ($variantValue) use ($variant_index, $variant_value_ids_query_parameter): VariantValueData {

                                    $is_product_first_variant = $variant_index == 0;

                                    if ($is_product_first_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->first_variant_value_id
                                            ==
                                            $variantValue->id;

                                        $first_variant_variant_value_has_combination_with_selected_second_and_third =
                                        (bool) $variantValue
                                            ->combined_by
                                            ->contains(function ($second_variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                $first_variant_value_has_combination_with_selected_second_variant_value =
                                                    $second_variant_value_with_pivot_variant_combination
                                                        ->id
                                                    ==
                                                    $variant_value_ids_query_parameter
                                                        ->second_variant_value_id;

                                                if ($first_variant_value_has_combination_with_selected_second_variant_value) {
                                                    $first_variant_value_and_second_has_combination_with_selected_third =
                                                        $second_variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->contains(function ($third_variant_value_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {
                                                                return
                                                                    $third_variant_value_with_pivot_second_variant_combination
                                                                        ->id
                                                                    ==
                                                                    $variant_value_ids_query_parameter
                                                                        ->third_variant_value_id;
                                                            });

                                                    return $first_variant_value_and_second_has_combination_with_selected_third;

                                                }

                                                return false;

                                            });

                                        $first_variant_query_parameter_variant_value_id =
                                            $variantValue
                                                ->id;

                                        if ($first_variant_variant_value_has_combination_with_selected_second_and_third) {

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
                                                    third_variant_value_id: $variant_value_ids_query_parameter
                                                        ->third_variant_value_id,
                                                )
                                            );
                                        }

                                        $first_variant_variant_value_first_combination_with_second_and_third =
                                            $variantValue
                                                ->combined_by
                                                ->first(function ($variant_value_with_pivot_variant_combination) {

                                                    $variant_combination_has_combination =
                                                        ! $variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->isEmpty();

                                                    return $variant_combination_has_combination;

                                                });

                                        $first_variant_variant_value_has_any_combination_with_second_and_third =
                                        (bool) $first_variant_variant_value_first_combination_with_second_and_third;

                                        if ($first_variant_variant_value_has_any_combination_with_second_and_third) {

                                            $second_vairant_value_id =
                                                $first_variant_variant_value_first_combination_with_second_and_third
                                                    ->id;

                                            $third_variant_value_id =
                                                $first_variant_variant_value_first_combination_with_second_and_third
                                                    ->pivot
                                                    ->combinations
                                                    ->first()
                                                    ->pivot
                                                    ->variant_value_id;

                                            return new VariantValueData(
                                                id: $variantValue->id,
                                                name: $variantValue->name,
                                                is_selected: $is_current_variant_value_selected,
                                                is_not_available: true,
                                                image: SingleMedia::fromModel($variantValue),
                                                combinations_ids_with_selected_variant_value: new GetProductDetailsQueryParameterData(
                                                    first_variant_value_id: $first_variant_query_parameter_variant_value_id,
                                                    second_variant_value_id: $second_vairant_value_id,
                                                    third_variant_value_id: $third_variant_value_id,
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

                                        $second_variant_variant_value_has_combination_with_selected_first_and_third =
                                            $variantValue
                                                ->combinations
                                                ->contains(function ($first_variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                    $second_variant_value_has_combination_with_first_variant_value =
                                                        $variant_value_ids_query_parameter
                                                            ->first_variant_value_id
                                                        ==
                                                        $first_variant_value_with_pivot_variant_combination
                                                            ->id;

                                                    if ($second_variant_value_has_combination_with_first_variant_value) {
                                                        return $first_variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->contains(function ($third_variant_value_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {
                                                                $second_and_first_variant_values_has_combination_with_third =
                                                                    $third_variant_value_with_pivot_second_variant_combination
                                                                        ->id
                                                                        ==
                                                                    $variant_value_ids_query_parameter
                                                                        ->third_variant_value_id;

                                                                return $second_and_first_variant_values_has_combination_with_third;
                                                            });

                                                    }

                                                    return false;

                                                });

                                        if ($second_variant_variant_value_has_combination_with_selected_first_and_third) {

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

                                        $second_variant_variant_value_first_combination_with_first_and_third =
                                            $variantValue
                                                ->combinations
                                                ->first(function ($second_variant_value_with_pivot_variant_combination) {

                                                    $variant_combination_has_combination =
                                                        ! $second_variant_value_with_pivot_variant_combination
                                                            ->pivot
                                                            ->combinations
                                                            ->isEmpty();

                                                    return $variant_combination_has_combination;

                                                });

                                        $second_variant_variant_value_has_any_combination_with_first_and_third =
                                            (bool) $second_variant_variant_value_first_combination_with_first_and_third;

                                        if ($second_variant_variant_value_has_any_combination_with_first_and_third) {

                                            Log::info('correct');

                                            Log::info($variant_value_ids_query_parameter
                                                ->second_variant_value_id);

                                            $first_vairant_value_id =
                                                $second_variant_variant_value_first_combination_with_first_and_third
                                                    ->id;

                                            $third_variant_value_id =
                                                $second_variant_variant_value_first_combination_with_first_and_third
                                                    ->pivot
                                                    ->combinations
                                                    ->first()
                                                    ->id;

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
                                                    third_variant_value_id: $third_variant_value_id
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
                                                        ->first_variant_value,
                                                    second_variant_value_id: $third_variant_variant_value_first_combination_with_first_and_second
                                                        ->second_variant_value,
                                                    third_variant_value_id: $third_variant_query_parameter_variant_value_id
                                                )
                                            );
                                        }

                                        $third_variant_value_with_no_combinations_with_first_and_second =
                                            new VariantValueData(
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

                                        return $third_variant_value_with_no_combinations_with_first_and_second;

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
                $selected_second_variant_value_with_pivot_variant_combination =
                    $selected_first_variant_value
                        ->combined_by
                        ->first(function ($second_variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                            $first_combination =
                                $second_variant_value_with_pivot_variant_combination
                                    ->id
                                ==
                                $variant_value_ids_query_parameter
                                    ->second_variant_value_id;

                            return $first_combination;

                        });

                $selected_second_variant_combination =
                    $selected_second_variant_value_with_pivot_variant_combination
                        ->pivot
                        ->combinations
                        ->first(function ($third_variant_value_with_pivot_second_variant_combination) use ($variant_value_ids_query_parameter) {

                            $variant_combination_has_combination_with_selected_third_variant_value =
                                $third_variant_value_with_pivot_second_variant_combination
                                    ->id
                                ==
                                $variant_value_ids_query_parameter
                                    ->third_variant_value_id;

                            return $variant_combination_has_combination_with_selected_third_variant_value;
                        })
                        ->pivot;

                $product_variation = new ProductVariationData(
                    id: $selected_second_variant_combination->id,
                    available: $selected_first_variant_value->available,
                    price: $selected_second_variant_combination->price,
                    image: SingleMedia::from($selected_second_variant_combination),
                );

                $x = new GetProductDetailsData(
                    id: $product->id,
                    variation: $product_variation,
                    name: $product->name,
                    price: $product->price,
                    is_favourite: (bool) $product->is_favourite,
                    image: SingleMedia::fromModel($product),
                    variants: $product_variants_data
                );

                return $x;
            }

            $product_with_no_variation = new self(
                id: $product->id,
                variation: null,
                name: $product->name,
                price: $product->price,
                is_favourite: (bool) $product->is_favourite,
                image: SingleMedia::fromModel($product),
                variants: $product_variants_data
            );

            Log::info('product with no variation {product}', ['product' => $product_with_no_variation]);

            return $product_with_no_variation;
        }

        //i.e small/red
        if ($product_variants_count == 2) {

            Log::info('product with two variants');

            /** @var Collection<int, VariantData> $product_variants_data */
            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant, int $variant_index) use ($variant_value_ids_query_parameter): VariantData {

                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function (VariantValue $variantValue) use ($variant_index, $variant_value_ids_query_parameter): VariantValueData {

                                    $is_product_first_variant = $variant_index == 0;

                                    if ($is_product_first_variant) {

                                        $is_current_variant_value_selected =
                                            $variant_value_ids_query_parameter
                                                ->first_variant_value_id
                                                ==
                                            $variantValue
                                                ->id;

                                        $first_variant_query_parameter_variant_value_id =
                                            $variantValue
                                                ->id;

                                        $first_variant_variant_value_has_combination_with_selected_second =
                                        (bool) $variantValue
                                            ->combined_by
                                            ->first(function ($second_variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                $first_variant_variant_value_has_combination_with_selected_second_variant_value =
                                                    $second_variant_value_with_pivot_variant_combination
                                                        ->id
                                                        ==
                                                    $variant_value_ids_query_parameter
                                                        ->second_variant_value_id;

                                                return $first_variant_variant_value_has_combination_with_selected_second_variant_value;

                                            });

                                        if ($first_variant_variant_value_has_combination_with_selected_second) {

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

                                        $first_variant_variant_value_first_combination_with_second =
                                            $variantValue
                                                ->combined_by
                                                ->first();

                                        $first_variant_variant_value_has_any_combination_with_second =
                                        (bool) $first_variant_variant_value_first_combination_with_second;

                                        if ($first_variant_variant_value_has_any_combination_with_second) {

                                            $second_vairant_value_id =
                                                $first_variant_variant_value_first_combination_with_second
                                                    ->id;

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

                                        $second_variant_query_parameter_variant_value_id =
                                            $variantValue
                                                ->id;

                                        $second_variant_variant_value_has_combination_with_selected_first =
                                        (bool) $variantValue
                                            ->combinations
                                            ->first(function ($first_variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                                                $second_variant_variant_value_has_combination_with_selected_first_variant_value =
                                                    $first_variant_value_with_pivot_variant_combination
                                                        ->id
                                                        ==
                                                    $variant_value_ids_query_parameter
                                                        ->first_variant_value_id;

                                                return $second_variant_variant_value_has_combination_with_selected_first_variant_value;

                                            });

                                        if ($second_variant_variant_value_has_combination_with_selected_first) {

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

                                        $second_variant_variant_value_first_combination_with_first =
                                            $variantValue
                                                ->combinations
                                                ->first();

                                        $current_variant_variant_value_has_any_combination_with_first =
                                        (bool) $second_variant_variant_value_first_combination_with_first;

                                        if ($current_variant_variant_value_has_any_combination_with_first) {

                                            $first_vairant_value_id =
                                                $second_variant_variant_value_first_combination_with_first
                                                    ->id;

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

            Log::info('all query parameters available');

            Log::info($variant_value_ids_query_parameter
                ->second_variant_value_id);

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

                Log::info('selected first variant value {selected_first_variant_value}', ['selected_first_variant_value' => $selected_first_variant_value]);

                $selected_second_variant_value_with_pivot_variant_combination =
                    $selected_first_variant_value
                        ->combined_by
                        ->first(function ($second_variant_value_with_pivot_variant_combination) use ($variant_value_ids_query_parameter) {

                            $selected_first_vairant_value_combination =
                                $second_variant_value_with_pivot_variant_combination
                                    ->id
                                ==
                                $variant_value_ids_query_parameter
                                    ->second_variant_value_id;

                            return $selected_first_vairant_value_combination;

                        });

                $selected_variant_combination =
                    $selected_second_variant_value_with_pivot_variant_combination
                        ->pivot;

                $product_variation = new ProductVariationData(
                    id: $selected_variant_combination
                        ->id,
                    available: $selected_variant_combination
                        ->available,
                    price: $selected_variant_combination
                        ->price,
                    image: SingleMedia::from($selected_variant_combination),
                );

                Log::info('product variation {product}', ['product' => $product_variants_data]);

                return new self(
                    id: $product->id,
                    variation: $product_variation,
                    name: $product->name,
                    price: $product->price,
                    is_favourite: (bool) $product->is_favourite,
                    image: SingleMedia::fromModel($product),
                    variants: $product_variants_data
                );
            }

            Log::info('product with two variants and no variation');

            $product_with_no_variation = new self(
                id: $product->id,
                variation: null,
                name: $product->name,
                price: $product->price,
                is_favourite: (bool) $product->is_favourite,
                image: SingleMedia::from($product),
                variants: $product_variants_data
            );

            return $product_with_no_variation;
        }

        //i.e small, medium etc.
        if ($product_variants_count == 1) {

            Log::info('product with one variant');

            /** @var Collection<int, VariantData> $product_variants_data */
            $product_variants_data =
                $product
                    ->variants
                    ->map(function (Variant $variant) use ($variant_value_ids_query_parameter): VariantData {

                        /** @var Collection<int, VariantValueData> $variant_values_data */
                        $variant_values_data =
                            $variant
                                ->variantValues
                                ->map(function ($variantValue) use ($variant_value_ids_query_parameter): VariantValueData {

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

            $product_variation = new ProductVariationData(
                id: $seleted_variant_value->id,
                available: $seleted_variant_value->available,
                price: $seleted_variant_value->price,
                image: SingleMedia::from($seleted_variant_value),
            );

            return new self(
                id: $product->id,
                variation: $product_variation,
                name: $product->name,
                price: $product->price,
                is_favourite: (bool) $product->is_favourite,
                image: SingleMedia::from($product),
                variants: $product_variants_data,
            );
        }

        Log::info('product with no variation');

        $product_with_no_variation =
            new self(
                id: $product->id,
                variation: null,
                name: $product->name,
                price: $product->price,
                is_favourite: (bool) $product->is_favourite,
                image: SingleMedia::from($product),
                variants: collect([]),
            );

        return $product_with_no_variation;
    }
}
