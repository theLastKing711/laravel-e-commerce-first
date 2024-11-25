<?php

namespace App\Models;

use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Log;

class VariantCombination extends Pivot
{
    use HasFactory, HasUlids;

    /**
     * Get the first_variant_value that owns the VariantCombination
     */
    public function first_variant_value(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class, 'first_variant_value_id');
    }

    /**
     * Get the first_variant_value that owns the VariantCombination
     */
    public function second_variant_value(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class, 'second_variant_value_id');
    }

    /**
     * Get all of the users that belong to the team.
     */
    public function combinations(): BelongsToMany
    {
        Debugbar::info('combinations');

        Log::info($this);

        return $this->belongsToMany(
            VariantValue::class,
            'second_variant_combination',
            'variant_combination_id',
            'variant_value_id'
        )
            ->withPivot('id', 'is_thumb')
            ->using(SecondVariantCombination::class);
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
