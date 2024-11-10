<?php

namespace App\Models;

use App\Observers\VariantCombinationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

#[ObservedBy(VariantCombinationObserver::class)]
/**
 * 
 *
 * @property int $id
 * @property int $first_variant_value_id
 * @property int $second_variant_value_id
 * @property int $is_thumb
 * @property string|null $price
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SecondVariantCombination> $combinations
 * @property-read int|null $combinations_count
 * @property-read \App\Models\VariantValue|null $first_variant
 * @property-read \App\Models\VariantValue|null $second_variant
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereFirstVariantValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereIsThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereSecondVariantValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
