<?php

namespace App\Services\VariantValue;

use App\Models\Product;
use App\Models\VariantValue;
use Illuminate\Support\Facades\DB;

class VariantValueCreationService
{
    public function handle(VariantValue $newly_created_variant_value): void
    {

        DB::transaction(function () use ($newly_created_variant_value) {

            $is_newly_created_variant_value_saved =
                $newly_created_variant_value
                    ->save();

            if ($is_newly_created_variant_value_saved) {
                $this->onVariantValueCreated(
                    $newly_created_variant_value
                );
            }
        });

    }

    public function onVariantValueCreated(
        VariantValue $newly_created_variant_value
    ) {
        $newly_created_variant_value_product =
            $newly_created_variant_value
                ->getProduct();

        $number_of_product_variants =
            $newly_created_variant_value_product
                ->getVariantsCount();

        $product_has_three_variants =
            $newly_created_variant_value_product
                ->hasThreeVariants();

        if ($product_has_three_variants) {

            $this->handleProductHasThreeVariants(
                $newly_created_variant_value_product,
                $newly_created_variant_value
            );

            return;
        }

        $product_has_two_variants =
            $newly_created_variant_value_product
                ->hasTwoVariants();

        if ($product_has_two_variants) {

            $this->handleProductHasTwoVariants(
                $newly_created_variant_value_product,
                $newly_created_variant_value
            );

            return;
        }

    }

    private function handleProductHasTwoVariants(
        Product $newly_created_variant_value_product,
        VariantValue $newly_created_variant_value
    ): void {

        $product_first_variant_id =
            $newly_created_variant_value_product
                ->variants
                ->first()
                ->id;

        $is_newly_created_variant_value_from_first_variant =
            $newly_created_variant_value
                ->variant_id
            ==
            $product_first_variant_id;

        if ($is_newly_created_variant_value_from_first_variant) {

            $product_second_variant_variant_values_ids =
                $newly_created_variant_value_product
                    ->getSecondVariantVariantValuesByFirstVariantValue(
                        $newly_created_variant_value
                    );

            $newly_created_variant_value
                ->attachCombinedByIds(
                    $product_second_variant_variant_values_ids
                );

            $newly_created_variant_value
                ->setCombinedByPricesToMaxValue(
                    $newly_created_variant_value_product
                );

            $product_has_thumb_variant_combined_by =
                $newly_created_variant_value_product
                    ->hasThumbVariantCombinedBy();

            if (! $product_has_thumb_variant_combined_by) {

                $newly_created_variant_value_product
                    ->refresh();

                $product_second_variant_first_variant_value_id =
                        $product_second_variant_variant_values_ids
                            ->first();

                $newly_created_variant_value
                    ->setCombinedByThumbToTrueById(
                        $product_second_variant_first_variant_value_id
                    );
            }

        }

        $is_newly_created_variant_value_from_second_variant =
            ! $is_newly_created_variant_value_from_first_variant;

        if ($is_newly_created_variant_value_from_second_variant) {

            $product_first_variant_variant_values_ids =
                $newly_created_variant_value_product
                    ->getFirstVariantVariantValuesIds();

            $newly_created_variant_value
                ->attachCombinationsIds(
                    $product_first_variant_variant_values_ids
                );

            $newly_created_variant_value
                ->setCombinationPricesToMaxValue(
                    $newly_created_variant_value_product
                );

            $product_has_thumb_variant_combination =
                $newly_created_variant_value_product
                    ->hasThumbVariantCombination();

            if (! $product_has_thumb_variant_combination) {

                $newly_created_variant_value_product
                    ->refresh();

                $product_first_variant_variant_value =
                        $product_first_variant_variant_values_ids
                            ->first();

                $newly_created_variant_value
                    ->setCombinationThumbToTrueById(
                        $product_first_variant_variant_value
                    );
            }

        }

    }

    private function handleProductHasThreeVariants(
        Product $newly_created_variant_value_product,
        VariantValue $newly_created_variant_value
    ): void {

        $product_variant_combinations_ids =
            $newly_created_variant_value_product
                ->getVariantCombinationsIds();

        $newly_created_variant_value
            ->attachLateCombinationsIds(
                $product_variant_combinations_ids
            );

        $newly_created_variant_value
            ->SetLateCombinationPricesToMaxValue(
                $newly_created_variant_value_product
            );

        $newly_created_variant_value_product
            ->refresh();

        $product_has_thumb_second_variant_combination =
            $newly_created_variant_value_product
                ->hasThumbSecondVariantCombination();

        if (! $product_has_thumb_second_variant_combination) {

            $product_first_second_variant_combination_id =
                $newly_created_variant_value_product
                    ->getFirstSecondVariantCombinationId();

            $newly_created_variant_value
                ->setLateCombinationThumbToTrueById(
                    $product_first_second_variant_combination_id
                );
        }

    }
}
