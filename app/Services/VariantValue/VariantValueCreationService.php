<?php

namespace App\Services\VariantValue;

use App\Models\Product;
use App\Models\Variant;
use App\Models\VariantValue;
use Illuminate\Support\Facades\DB;
use Log;

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

        Log::info($newly_created_variant_value_product->variants()->count());

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

            Log::info('hello world');

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

        $product_other_variants_variant_values_ids =
            $newly_created_variant_value_product
                ->getOtherVariantsVariantValueIdsByVariantValueId(
                    $newly_created_variant_value->id
                );

        $newly_created_variant_value
            ->attachCombinationsIds(
                $product_other_variants_variant_values_ids
            );

        $newly_created_variant_value
            ->setCombinationPricesToMaxValue(
                $newly_created_variant_value_product
            );

        $newly_created_variant_value_product
            ->refresh();

        $product_has_thumb_variant_combination =
            $newly_created_variant_value_product
                ->hasThumbVariantCombination();

        if (! $product_has_thumb_variant_combination) {

            $product_first_other_variants_variant_values_id =
                $product_other_variants_variant_values_ids
                    ->first();

            $newly_created_variant_value
                ->setCombinationThumbToTrueById(
                    $product_first_other_variants_variant_values_id
                );
        }

    }

    private function handleProductHasThreeVariants(
        Product $newly_created_variant_value_product,
        VariantValue $newly_created_variant_value
    ): void {

        $product_variant_combinations_ids =
            $newly_created_variant_value_product
                ->getVariantCombinationsIds();

        // Log::info($product_variant_combinations_ids);

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

            // Log::info(VariantValue::with('late_combinations')->firstWhere('id', $newly_created_variant_value->id));
            Log::info('test');

            Log::info($newly_created_variant_value->id);

            Log::info(VariantValue::with('late_combinations')->firstWhere('id', $newly_created_variant_value->id));

            // $variant_value = VariantValue::query()->where('id', $newly_created_variant_value)->first()->combinations;

            // Log::info($variant_value);

            // Log::info($newly_created_variant_value_product
            //     ->variants
            //     ->pluck('variantValues')
            //     ->flatten());

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
