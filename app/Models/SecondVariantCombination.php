<?php

namespace App\Models;

use App\Observers\SecondVariantCombinationObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

// #[ObservedBy(SecondVariantCombinationObserver::class)]
/**
 * @property int $id
 * @property int $variant_combination_id
 * @property int $variant_value_id
 * @property int $is_thumb
 * @property string|null $price
 * @property int $quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereIsThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereVariantCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereVariantValueId($value)
 *
 * @mixin \Eloquent
 */
class SecondVariantCombination extends Pivot
{
    protected $table = 'second_variant_combination';

    use HasFactory, HasUuids;

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
