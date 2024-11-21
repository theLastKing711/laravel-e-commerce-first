<?php

namespace App\Models;

use App\Enum\OrderStatus;
use Attribute;
use Database\Factories\OrderFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * 
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $total
 * @property int $status
 * @property string|null $rejection_reason
 * @property string $required_time
 * @property string|null $notice
 * @property float $lat
 * @property float $lon
 * @property string|null $accepted_at
 * @property string|null $on_the_way_at
 * @property string|null $rejected_at
 * @property string|null $completed_at
 * @property string $delivery_price
 * @property int $user_id
 * @property int|null $coupon_id
 * @property int|null $driver_id
 * @property-read Coupon|null $coupon
 * @property-read User|null $driver
 * @property-read Collection<int, OrderDetails> $orderDetails
 * @property-read int|null $order_details_count
 * @property-read User $user
 * @method static OrderFactory factory($count = null, $state = [])
 * @method static Builder|Order newModelQuery()
 * @method static Builder|Order newQuery()
 * @method static Builder|Order query()
 * @method static Builder|Order whereAcceptedAt($value)
 * @method static Builder|Order whereCompletedAt($value)
 * @method static Builder|Order whereCouponId($value)
 * @method static Builder|Order whereCreatedAt($value)
 * @method static Builder|Order whereDeliveryPrice($value)
 * @method static Builder|Order whereDriverId($value)
 * @method static Builder|Order whereId($value)
 * @method static Builder|Order whereLat($value)
 * @method static Builder|Order whereLon($value)
 * @method static Builder|Order whereNotice($value)
 * @method static Builder|Order whereOnTheWayAt($value)
 * @method static Builder|Order whereRejectedAt($value)
 * @method static Builder|Order whereRejectionReason($value)
 * @method static Builder|Order whereRequiredTime($value)
 * @method static Builder|Order whereStatus($value)
 * @method static Builder|Order whereTotal($value)
 * @method static Builder|Order whereUpdatedAt($value)
 * @method static Builder|Order whereUserId($value)
 * @mixin Eloquent
 */
class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function driver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    // protected function status(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (int $value) => OrderStatus::from($value),
    //         set: function (OrderStatus $value) {
    //             return $value->value;
    //         }
    //     );
    // }

    //int when saved to db, and enum when retrieved from database
    protected function casts(): array
    {
        return [
            'status' => OrderStatus::class,
        ];
    }

    public function getTotalWithShipmentAndDiscount(): float
    {
        $order_items = $this->orderDetails;

        $order_total_before_delivery_and_discount = $order_items->sum(function (OrderDetails $item) {
            $price = $item->unit_price ?? $item->unit_price_offer;

            return $price * $item->quantity;
        });

        $order_coupon_value = $order->coupon?->value ?? 0;

        $order_discount_value =
            ($order_coupon_value * $order_total_before_delivery_and_discount) / 100;

        $order_delivery_price = $this->delivery_price;

        $total_after_shipment_and_delivery = $order_total_before_delivery_and_discount + $order_delivery_price - $order_discount_value;

        return $total_after_shipment_and_delivery;

    }
}
