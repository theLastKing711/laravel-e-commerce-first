<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $total
 * @property string $status
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
 * @property int|null $driver_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetails> $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\OrderFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAcceptedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCompletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDeliveryPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDriverId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOnTheWayAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRejectedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRejectionReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRequiredTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }
}
