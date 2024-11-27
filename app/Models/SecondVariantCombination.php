<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * 
 *
 * @property string $id
 * @property string $variant_combination_id
 * @property string $variant_value_id
 * @property int $is_thumb
 * @property string|null $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VariantCombination|null $variantCombination
 * @property-read \App\Models\VariantValue|null $variantValue
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereIsThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereVariantCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereVariantValueId($value)
 * @mixin \Eloquent
 */
class SecondVariantCombination extends Pivot
{
    use EagerLoadPivotTrait, HasFactory, HasUlids;

    /**
     * Get the combination that owns the SecondVariantCombination
     */
    public function variantCombination(): BelongsTo
    {
        return $this->belongsTo(VariantCombination::class, 'variant_combination_id');
    }

    public function variantValue(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class, 'variant_value_id');
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
                        'late_combinations',
                    ],
                ],
            ])
            ->whereHas('variants', function ($query) { // select the product that has variants that has varian_value with id of $newly_created_variant_value
                $query->whereHas('variantValues', function ($query) {
                    $query->whereHas('late_combinations', function ($query) {
                        $query->where('id', $this->id);
                    });
                });
            })
            ->first();
    }
}
