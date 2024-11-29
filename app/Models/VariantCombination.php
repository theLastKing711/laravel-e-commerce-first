<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Interfaces\Mediable;
use Barryvdh\Debugbar\Facades\Debugbar;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Log;

/**
 * 
 *
 * @property string $id
 * @property string $first_variant_value_id
 * @property string $second_variant_value_id
 * @property int $is_thumb
 * @property string|null $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantValue,\App\Models\SecondVariantCombination> $combinations
 * @property-read int|null $combinations_count
 * @property-read \App\Models\VariantValue|null $first_variant_value
 * @property-read \App\Models\VariantValue|null $second_variant_value
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereFirstVariantValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereIsThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereSecondVariantValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \CloudinaryLabs\CloudinaryLaravel\Model\Media> $medially
 * @property-read int|null $medially_count
 * @mixin \Eloquent
 */
class VariantCombination extends Pivot implements Mediable
{
    use EagerLoadPivotTrait,HasFactory, HasUlids, MediaAlly;

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
