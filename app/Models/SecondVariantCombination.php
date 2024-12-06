<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \CloudinaryLabs\CloudinaryLaravel\Model\Media> $medially
 * @property-read int|null $medially_count
 * @property-read \App\Models\VariantCombination $variantCombination
 * @property-read \App\Models\VariantValue $variantValue
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination newModelQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination newQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination query()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereAvailable($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereCreatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereIsThumb($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination wherePrice($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereUpdatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereVariantCombinationId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|SecondVariantCombination whereVariantValueId($value)
 * @mixin \Eloquent
 */
class SecondVariantCombination extends Pivot implements Mediable
{
    use EagerLoadPivotTrait, HasUlids, MediaAlly;

    public function medially(): MorphMany
    {
        return $this->morphMany(Media::class, 'medially');
    }

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
                            'pivot' => [
                                'combinations',
                            ],
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
