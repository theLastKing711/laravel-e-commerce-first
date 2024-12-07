<?php

namespace App\Models;

use AjCastro\EagerLoadPivotRelations\EagerLoadPivotTrait;
use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Eloquent;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;

/**
 * 
 *
 * @property string $id
 * @property string $variant_id
 * @property int $is_thumb
 * @property string $name
 * @property string $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantValue,\App\Models\VariantCombination> $combinations
 * @property-read int|null $combinations_count
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantValue,\App\Models\VariantCombination> $combined_by
 * @property-read int|null $combined_by_count
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantCombination,\App\Models\SecondVariantCombination> $late_combinations
 * @property-read int|null $late_combinations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medially
 * @property-read int|null $medially_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SecondVariantCombination> $second_level_combinations
 * @property-read int|null $second_level_combinations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SecondVariantCombination> $second_level_combined_by
 * @property-read int|null $second_level_combined_by_count
 * @property-read \App\Models\Variant $variant
 * @method static \Database\Factories\VariantValueFactory factory($count = null, $state = [])
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue newModelQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue newQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue query()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereAvailable($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereCreatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereIsThumb($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereName($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue wherePrice($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereUpdatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereVariantId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\SecondVariantCombination|\App\Models\VariantCombination|null $pivot
 * @mixin Eloquent
 */
class VariantValue extends Eloquent implements Mediable
{
    use EagerLoadPivotTrait, HasFactory, HasUlids, MediaAlly;

    public function medially(): MorphMany
    {
        return $this->morphMany(Media::class, 'medially');
    }

    /**
     * Get the variant that owns the VariantValue
     */
    public function variant(): BelongsTo
    {
        return $this->belongsTo(Variant::class);
    }

    public function combinations(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                VariantValue::class,
                'variant_combination',
                'first_variant_value_id',
                'second_variant_value_id'
            )
            ->withPivot('id', 'is_thumb', 'price', 'available')
            ->using(VariantCombination::class);
    }

    public function combined_by(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                VariantValue::class,
                'variant_combination',
                'second_variant_value_id',
                'first_variant_value_id',
            )
            ->withPivot('id', 'is_thumb', 'price', 'available')
            ->using(VariantCombination::class);
    }

    public function late_combinations(): BelongsToMany
    {
        return
            $this
                ->belongsToMany(
                    VariantCombination::class,
                    'second_variant_combination',
                    'variant_value_id',
                    'variant_combination_id',
                )
                ->withPivot('id', 'is_thumb', 'price', 'available')
                ->using(SecondVariantCombination::class);
    }

    //variant value second_variant_combination through variant_combination
    public function second_level_combinations(): HasManyThrough
    {
        return $this->hasManyThrough(
            SecondVariantCombination::class,
            VariantCombination::class,
            'first_variant_value_id', // Foreign key on the variant_combaination table...
            'variant_combination_id', // Foreign key on the second_variant_combaination table
        );
    }

    //variant value second_variant_combination through variant_combination
    public function second_level_combined_by(): HasManyThrough
    {
        return $this->hasManyThrough(
            SecondVariantCombination::class,
            VariantCombination::class,
            'second_variant_value_id', // Foreign key on the variant_combaination table...
            'variant_combination_id', // Foreign key on the second_variant_combaination table
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
                        'late_combinations',
                    ],
                ],
            ])
            ->whereHas('variants', function ($query) { // select the product that has variants that has varian_value with id of $newly_created_variant_value
                $query->whereHas('variantValues', function ($query) {

                    $query->where('id', $this->id);
                });
            })
            ->first();
    }

    /**
     * @param  Collection<int,string>  $combinations_ids
     */
    public function attachCombinationsIds(Collection $combinations_ids): void
    {
        $this
            ->combinations()
            ->attach(
                $combinations_ids,
                ['is_thumb' => false, 'available' => 0]
            );
    }

    /**
     * @param  Collection<int,string>  $combinations_ids
     */
    public function attachCombinedByIds(Collection $combinations_ids): void
    {
        $this
            ->combined_by()
            ->attach(
                $combinations_ids,
                ['is_thumb' => false, 'available' => 0]
            );
    }

    /** @param Collection<int, string> $combinations_ids */
    public function attachLateCombinationsIds(Collection $combinations_ids): void
    {
        $this
            ->late_combinations()
            ->attach(
                $combinations_ids,
                ['is_thumb' => false, 'available' => 0]
            );
    }

    public function setCombinationPricesToMaxValue(Product $product)
    {
        $product_price = $product
                            ->price;

        $max_of_product_price_and_main_variant_value_price =
            max($product_price, $this->price);

        $this
            ->combinations
            ->each(function ($first_variant_value_with_pivot_variant_combination) use ($max_of_product_price_and_main_variant_value_price) {

                $variant_combinations =
                    $first_variant_value_with_pivot_variant_combination
                    ->pivot;

                $new_media =
                    Media::factory(1)
                        ->makeOne();

                $variant_combinations
                    ->medially()
                    ->save($new_media);

                $max_price = max($max_of_product_price_and_main_variant_value_price, $first_variant_value_with_pivot_variant_combination->price);

                VariantCombination::query()
                    ->firstWhere('id', $first_variant_value_with_pivot_variant_combination->pivot->id)
                    ->update(['price' => $max_price]);

            });

    }

    public function setCombinedByPricesToMaxValue(Product $product)
    {
        $product_price =
            $product
                ->price;

        $max_of_product_price_and_main_variant_value_price =
            max($product_price, $this->price);

        $this
            ->combined_by()
            ->each(function ($variantValue) use ($max_of_product_price_and_main_variant_value_price) {

                $max_price = max($max_of_product_price_and_main_variant_value_price, $variantValue->price);

                VariantCombination::query()
                    ->firstWhere('id', $variantValue->pivot->id)
                    ->update(['price' => $max_price]);

            });

    }

    public function SetLateCombinationPricesToMaxValue(Product $product)
    {
        $product_price = $product->price;

        $max_of_product_price_and_main_variant_value_price = max($product_price, $this->price);

        $this
            ->late_combinations
            ->each(function ($third_variantValue_with_pivot_second_variant_combination, $index) use ($max_of_product_price_and_main_variant_value_price) {

                $second_variant_combination = $third_variantValue_with_pivot_second_variant_combination->pivot;

                $max_price = max($max_of_product_price_and_main_variant_value_price, $second_variant_combination->price);

                $new_media =
                    Media::factory(1)
                        ->makeOne();

                $second_variant_combination
                    ->medially()
                    ->save($new_media);

                $second_variant_combination
                    ->update(['price' => $max_price]);

            });
    }

    public function setCombinationThumbToTrueById(string $variant_value_id)
    {
        $this
            ->combinations()
            ->updateExistingPivot(
                $variant_value_id,
                ['is_thumb' => true]
            );
    }

    public function setCombinedByThumbToTrueById(string $variant_value_id)
    {
        $this
            ->combined_by()
            ->updateExistingPivot(
                $variant_value_id,
                ['is_thumb' => true]
            );
    }

    public function setLateCombinationThumbToTrueById(string $second_varaiont_combination_id)
    {
        $this
            ->late_combinations()
            ->updateExistingPivot(
                $second_varaiont_combination_id,
                ['is_thumb' => true]
            );
    }
}
