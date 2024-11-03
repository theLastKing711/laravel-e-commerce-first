<?php

namespace App\Models;

use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

//example small, big, large, cheese, salt
class VariantValue extends Model
{
    use HasFactory, MediaAlly;

    public static function booted(): void
    {
        static::created(function (VariantValue $variant_value) {
            $product_price = $variant_value->variant->product->price;

            $max_of_product_price_and_main_variant_value_price = max($product_price, $variant_value->price);

            VariantValue::query()
                ->whereNot('variant_id', $variant_value->variant->id)
                ->where('variant.product.id', $variant_value->product->id)
                ->each(function (VariantValue $variant_value_to_add) use ($variant_value, $max_of_product_price_and_main_variant_value_price) {
                    $max_price = max($max_of_product_price_and_main_variant_value_price, $variant_value_to_add->price);
                    $variant_value->combinations()->attach($variant_value_to_add->id, ['price' => $max_price]);
                });
        });
    }

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

    /**
     * Get all of the combinations for the VariantValue
     */
    //we combine both combinatin and combined_by using union
    //to get the result for a specifc variant value
    //exmple small(id) and pizza(id) or medium(id) and salt(id)
    public function combinations(): BelongsToMany
    {
        return $this->belongsToMany(
            VariantValue::class,
            'variant_combination',
            'first_variant_value_id',
            'second_variant_value_id'
        );
    }

    /**
     * Get all of the combinations for the VariantValue
     */
    //exmple small(id) and pizza(id) or medium(id) and salt(id)
    public function combined_by(): BelongsToMany
    {
        // return Product::categories()->sync();

        return $this->belongsToMany(
            VariantValue::class,
            'variant_combination',
            'second_variant_value_id',
            'first_variant_value_id',
        );
    }

    //combination of variant_combination and variant value
    public function late_combinations(): BelongsToMany
    {
        return $this->belongsToMany(
            VariantValue::class,
            'second_variant_combination',
            'variant_id',
            'first_variant_value_id',
        );
    }

    //variant value second_variant_combination through variant_combination
    public function late_combined_by(): HasManyThrough
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
            'first_variant_value_id', // Foreign key on the variant_combaination table...
            'variant_combination_id', // Foreign key on the second_variant_combaination table
        );
    }

    // public function generateValuesCombinations()
    // {
    //     $product_price = $this->variant->product->price;

    //     $max_of_product_price_and_main_variant_price = max($product_price, $this->price);

    //     $all_but_current_variant_values = VariantValue::query()
    //         ->whereNot('variant_id', $this->variant->id)
    //         ->where('variant.product.id', $this->product->id)
    //         ->each(function (VariantValue $variant_value_to_add) use ($this, $max_of_product_price_and_main_variant_price) {
    //             $max_price = max($max_of_product_price_and_main_variant_price, $variant_value_to_add->price);
    //             $this->combinations()->attach($variant_value_to_add->id, ['price' => $max_price]);
    //         });

    // }
}
