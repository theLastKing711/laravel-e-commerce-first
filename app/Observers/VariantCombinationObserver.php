<?php

namespace App\Observers;

use App\Models\VariantCombination;

class VariantCombinationObserver
{
    /**
     * Handle the VariantCombination "created" event.
     */
    public function created(VariantCombination $variantCombination): void
    {
        //
    }

    /**
     * Handle the VariantCombination "updated" event.
     */
    public function updated(VariantCombination $variantCombination): void
    {
        //
    }

    /**
     * Handle the VariantCombination "deleted" event.
     */
    public function deleted(VariantCombination $newly_deleted_variant_combination): void
    {
        if ($newly_deleted_variant_combination->is_thumb) {

            $newly_deleted_variant_combination_product =
                $newly_deleted_variant_combination
                    ->getProduct();

            $product_has_two_variants =
                $newly_deleted_variant_combination_product
                    ->hasTwoVariants();

            if ($product_has_two_variants) {
                $newly_deleted_variant_combination_product
                    ->setFirstVariantCombinationThumbToTrue();
            }

        }
    }

    /**
     * Handle the VariantCombination "restored" event.
     */
    public function restored(VariantCombination $variantCombination): void
    {
        //
    }

    /**
     * Handle the VariantCombination "force deleted" event.
     */
    public function forceDeleted(VariantCombination $variantCombination): void
    {
        //
    }
}
