<?php

namespace App\Observers;

use App\Models\SecondVariantCombination;

class SecondVariantCombinationObserver
{
    /**
     * Handle the SecondVariantCombination "created" event.
     */
    public function created(SecondVariantCombination $secondVariantCombination): void
    {
        //
    }

    /**
     * Handle the SecondVariantCombination "updated" event.
     */
    public function updated(SecondVariantCombination $secondVariantCombination): void
    {
        //
    }

    /**
     * Handle the SecondVariantCombination "deleted" event.
     */
    public function deleted(SecondVariantCombination $newly_deleted_second_variant_combinations): void
    {
        $newly_deleted_second_variant_combinations_product =
            $newly_deleted_second_variant_combinations
                ->getProduct();

        $product_has_three_variations =
            $newly_deleted_second_variant_combinations_product
                ->hasThreeVariants();

        if ($product_has_three_variations) {
            $newly_deleted_second_variant_combinations_product
                ->setFirstSecondVariantCombinationThumbToTrue();
        }

    }

    /**
     * Handle the SecondVariantCombination "restored" event.
     */
    public function restored(SecondVariantCombination $secondVariantCombination): void
    {
        //
    }

    /**
     * Handle the SecondVariantCombination "force deleted" event.
     */
    public function forceDeleted(SecondVariantCombination $secondVariantCombination): void
    {
        //
    }
}
