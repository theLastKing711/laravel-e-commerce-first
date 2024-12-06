<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\Pivot;

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
 * @property-read \App\Models\VariantValue $first_variant_value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \CloudinaryLabs\CloudinaryLaravel\Model\Media> $medially
 * @property-read int|null $medially_count
 * @property-read \App\Models\VariantValue $second_variant_value
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination newModelQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination newQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination query()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereAvailable($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereCreatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereFirstVariantValueId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereIsThumb($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination wherePrice($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereSecondVariantValueId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantCombination whereUpdatedAt($value)
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

        return $this->belongsToMany(
            VariantValue::class,
            'second_variant_combination',
            'variant_combination_id',
            'variant_value_id'
        )
            ->withPivot('id', 'is_thumb', 'price', 'available')
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
