<?php

namespace App\Services\VariantValueCombination;

use App\Models\VariantCombination;
use Illuminate\Support\Facades\DB;

class VariantValueCombinationDeletionService
{
    public function handle(
        VariantCombination $variant_combination_to_delete
    ): void {

        DB::transaction(function () use ($variant_combination_to_delete) {

            $is_variant_combination_deleted =
                $variant_combination_to_delete->delete();

            if ($is_variant_combination_deleted) {
                $this->onVariantCombinationDeleted(
                    $variant_combination_to_delete
                );
            }
        });

    }

    public function onVariantCombinationDeleted(
        VariantCombination $newly_deleted_variant_combination
    ) {
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
}
