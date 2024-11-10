<?php

namespace App\Models;

use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

//example size, flavour .. etc
class Variant extends Model implements Mediable
{
    use HasFactory, MediaAlly;

    public function medially(): MorphMany
    {
        return $this->morphMany(Media::class, 'medially');
    }

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

    /**
     * Get all of the combinations for the Variant
     */
    public function combinations(): HasManyThrough
    {
        return $this->hasManyThrough(VariantCombination::class, VariantValue::class, 'variant_id', 'first_variant_value_id');
    }
}
