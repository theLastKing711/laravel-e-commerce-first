<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

//example size, flavour .. etc
class Variant extends Model
{
    use HasFactory;

    /**
     * Get the product that owns the Variant
     *
     * @return \Illuminate\Product\Eloquent\Relations\BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get all of the variantValues for the Variant
     */
    public function variantValues(): HasMany
    {
        return $this->hasMany(VariantValue::class);
    }

    // public function generateValuesCombinations()
    // {
    //     $product_price = $this->product->price;

    //     $all_but_current_variant_values = VariantValue::query()
    //         ->whereNot('variant_id', $this->id)
    //         ->where('variant.product.id', $this->product_id)
    //         ->get();

    //     $this->variantValues()
    //         ->each(function (VariantValue $variant_value) use ($all_but_current_variant_values, $product_price) {

    //             $max_price = max($product_price, $variant_value->price);

    //             $variant_value->combinations()
    //                 ->saveMany($all_but_current_variant_values, ['price' => $max_price]);
    //         });
    // }
}
