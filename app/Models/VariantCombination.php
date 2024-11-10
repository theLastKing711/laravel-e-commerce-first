<?php

namespace App\Models;

use App\Observers\VariantCombinationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy(VariantCombinationObserver::class)]
class VariantCombination extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    use HasFactory;

    /**
     * Get the first_variant that owns the VariantCombination
     */
    public function first_variant(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class, 'first_variant_value_id');
    }

    /**
     * Get the first_variant that owns the VariantCombination
     */
    public function second_variant(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class, 'second_variant_value_id');
    }

    /**
     * The combinations that belong to the VariantCombination
     */
    public function combinations(): BelongsToMany
    {
        return $this->belongsToMany(
            SecondVariantCombination::class,
            'second_variant_combination',
            'variant_combination_id',
            'variant_id'
        );
    }

    public function getProduct(): Product
    {
        return Product::query()
            ->with([
                'variants' => [
                    'variantValues' => [
                        'variant',
                        'combinations' => [
                            'combinations',
                        ],
                        'combinations',
                    ],
                ],
            ])
            ->whereHas('variants', function ($query) { // select the product that has variants that has varian_value with id of $newly_created_variant_value
                $query->whereHas('variantValues', function ($query) {
                    $query->whereHas('combinations', function ($query) {
                        $query->where('id', $this->id);
                    });
                });
            })
            ->first();
    }
}
