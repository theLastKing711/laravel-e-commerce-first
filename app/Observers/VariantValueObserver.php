<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\VariantValue;
use Log;

class VariantValueObserver
{
    /**
     * Handle the VariantValue "created" event.
     */
    public function created(VariantValue $newly_created_variant_value): void
    {
        $newly_created_variant_value_product =
            $newly_created_variant_value
                ->getProduct(
                    $newly_created_variant_value
                );

        $number_of_product_variants =
            $newly_created_variant_value_product
                ->getVariantsCount();

        $product_has_one_variant =
            $this->productHasOneVariant(
                $number_of_product_variants
            );

        if ($product_has_one_variant) {
            return;
        }

        $product_has_two_variants =
            $this->productHasTwoVariants(
                $number_of_product_variants
            );

        // add to variant_combination table i.e small/green small/blue
        if ($product_has_two_variants) {

            $this->handleProductHasTwoVariants(
                $newly_created_variant_value_product,
                $newly_created_variant_value
            );

            return;
        }

        $product_has_three_variants =
            $this->handleProductHasThreeVariants(
                $newly_created_variant_value_product,
                $newly_created_variant_value
            );

        if ($product_has_three_variants) {

            $this->handleProductHasThreeVariants(
                $newly_created_variant_value_product,
                $newly_created_variant_value
            );
        }

    }

    private function handleProductHasTwoVariants(Product $newly_created_variant_value_product, VariantValue $newly_created_variant_value)
    {
        $product_other_variants_variant_values_ids =
        $newly_created_variant_value_product
            ->getOtherVariantsVariantValueIds(
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

    private function handleProductHasThreeVariants(Product $newly_created_variant_value_product, VariantValue $newly_created_variant_value)
    {

        $product_variant_combinations_ids =
            $newly_created_variant_value_product
                ->getVariantCombinationsIds(
                    $newly_created_variant_value_product
                );

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

    /**
     * Handle the VariantValue "updated" event.
     */
    public function updated(VariantValue $variantValue): void
    {
        Log::info($variantValue);

    }

    /**
     * Handle the VariantValue "deleted" event.
     */
    public function deleted(VariantValue $newly_deleted_variant_value): void
    {
        $newly_deleted_variant_value_product =
            $newly_deleted_variant_value
                ->getProduct();

        $product_variants_count =
            $newly_deleted_variant_value_product
                ->getVariantsCount();

        $product_has_no_variant =
            $this->productHasNoVariant(
                $product_variants_count
            );

        if ($product_has_no_variant) {
            return;
        }

        $product_has_one_variant =
            $this->productHasOneVariant(
                $product_variants_count
            );

        if ($product_has_one_variant) {

            if ($newly_deleted_variant_value->is_thumb) {

                $newly_deleted_variant_value_product
                    ->setFirstVariantValueThumbToTrue();
            }
        }

        $product_has_two_variant =
            $this->productHasTwoVariants(
                $product_variants_count
            );

        if ($product_has_two_variant) {

            $product_has_thumb_variant_combination =
                $newly_deleted_variant_value_product
                    ->hasThumbVariantCombination();

            if (! $product_has_thumb_variant_combination) {

                $newly_deleted_variant_value_product
                    ->setFirstVariantCombinationThumbToTrue();
            }
        }

        $product_has_three_variant =
            $this->productHasThreeVariants(
                $product_variants_count
            );

        if ($product_has_three_variant) {

            $product_has_secondary_thumb_variant_combination =
            $newly_deleted_variant_value_product
                ->hasThumbSecondVariantCombination();

            if (! $product_has_secondary_thumb_variant_combination) {
                $newly_deleted_variant_value_product
                    ->setFirstSecondVariantCombinationValueThumbToTrue();
            }

        }

    }

    /**
     * Handle the VariantValue "restored" event.
     */
    public function restored(VariantValue $variantValue): void
    {
        //
    }

    /**
     * Handle the VariantValue "force deleted" event.
     */
    public function forceDeleted(VariantValue $variantValue): void
    {
        //
    }

    private function productHasNoVariant(int $number_of_product_variants): bool
    {
        return $number_of_product_variants === 0;
    }

    private function productHasOneVariant(int $number_of_product_variants): bool
    {
        return $number_of_product_variants === 1;
    }

    private function productHasTwoVariants(int $number_of_product_variants): bool
    {
        return $number_of_product_variants === 2;
    }

    private function productHasThreeVariants(int $number_of_product_variants): bool
    {
        return $number_of_product_variants === 3;
    }
}
