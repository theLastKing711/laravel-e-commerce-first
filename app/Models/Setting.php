<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property float $km_price
 * @property float $open_km_price
 * @property float $order_delivery_min_distance
 * @property float $order_delivery_min_item_per_order
 * @property float $min_order_item_quantity_for_free_delivery
 * @property float $store_lat
 * @property float $store_lon
 * @property string $address
 * @property string $work_days
 * @method static \Database\Factories\SettingFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting query()
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereKmPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereMinOrderItemQuantityForFreeDelivery($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOpenKmPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOrderDeliveryMinDistance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereOrderDeliveryMinItemPerOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStoreLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereStoreLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Setting whereWorkDays($value)
 * @mixin \Eloquent
 * @mixin Eloquent
 */
class Setting extends Eloquent
{
    use HasFactory;
}
