
<?php

namespace App\Services\VariantValue;

use App\Models\VariantValue;
use Illuminate\Support\Facades\DB;

class VariantValueDeletionService
{
    /**
     * Handle the VariantValue "deleted" event.
     */
    public function handle(VariantValue $variant_value_to_delete): void
    {

        DB::transaction(function () use ($variant_value_to_delete) {

            $variant_value_to_delete->delete();

            $this->onVariantValueDeleted($variant_value_to_delete);

        });
    }

    public function onVariantValueDeleted(
        VariantValue $newly_deleted_variant_value
    ) {
        $newly_deleted_variant_value_product =
            $newly_deleted_variant_value
                ->getProduct();

        $product_has_no_variant =
            $newly_deleted_variant_value_product
                ->productHasNoVariant();

        if ($product_has_no_variant) {
            return;
        }

        $product_has_one_variant =
            $newly_deleted_variant_value_product
                ->productHasOneVariant();

        if ($product_has_one_variant) {

            if ($newly_deleted_variant_value->is_thumb) {

                $newly_deleted_variant_value_product
                    ->setFirstVariantValueThumbToTrue();
            }
        }

        $product_has_two_variant =
            $newly_deleted_variant_value_product
                ->productHasTwoVariants();

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
            $newly_deleted_variant_value_product
                ->productHasThreeVariants();

        if ($product_has_three_variant) {

            $product_has_secondary_thumb_variant_combination =
                $newly_deleted_variant_value_product
                    ->hasThumbSecondVariantCombination();

            if (! $product_has_secondary_thumb_variant_combination) {
                $newly_deleted_variant_value_product
                    ->setFirstSecondVariantCombinationThumbToTrue();
            }

        }
    }
}
