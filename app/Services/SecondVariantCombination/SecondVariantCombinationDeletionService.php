<?php

namespace App\Services\SecondVariantCombinationDeletionService;

use App\Models\SecondVariantCombination;
use Illuminate\Support\Facades\DB;

class VariantValueCreationService
{
    public function handle(
        SecondVariantCombination $second_variant_combination_to_delete
    ): void {

        $second_variant_combination_to_delete
            ->delete();

        DB::transaction(function () use ($second_variant_combination_to_delete) {
            $this->onSecondVariantCombinationDelete(
                $second_variant_combination_to_delete
            );
        });

    }

    public function onSecondVariantCombinationDelete(
        SecondVariantCombination $newly_deleted_second_variant_combinations
    ) {
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
}
