<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

class VariantCombination extends Pivot
{
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
}
