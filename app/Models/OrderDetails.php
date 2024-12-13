<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $order_id
 * @property int $product_id
 * @property string|null $unit_price
 * @property string|null $unit_price_offer
 * @property int $quantity
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Product $product
 *
 * @method static \Database\Factories\OrderDetailsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereUnitPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereUnitPriceOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderDetails whereUpdatedAt($value)
 *
 * @property string|null $variant_value_id
 * @property string|null $variant_combination_id
 * @property string|null $second_variant_combination_id
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetails whereSecondVariantCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetails whereVariantCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderDetails whereVariantValueId($value)
 *
 * @property-read \App\Models\SecondVariantCombination|null $secondVariantCombination
 * @property-read \App\Models\VariantCombination|null $variantCombination
 * @property-read \App\Models\VariantValue|null $variantValue
 *
 * @mixin Eloquent
 */
class OrderDetails extends Eloquent
{
    use HasFactory;

    protected $guarded = ['id'];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the variantValue that owns the OrderDetails
     */
    public function variantValue(): BelongsTo
    {
        return $this->belongsTo(VariantValue::class);
    }

    /**
     * Get the variantCombination that owns the OrderDetails
     */
    public function variantCombination(): BelongsTo
    {
        return $this->belongsTo(VariantCombination::class);
    }

    /**
     * Get the secondVariantCombination that owns the OrderDetails
     */
    public function secondVariantCombination(): BelongsTo
    {
        return $this->belongsTo(SecondVariantCombination::class);
    }
}
