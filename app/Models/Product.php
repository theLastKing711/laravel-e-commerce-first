<?php

namespace App\Models;

use App\Enum\Unit;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Database\Factories\ProductFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Log;

use function explode;
use function str_contains;
use function strlen;

/**
 * 
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $name
 * @property string $price
 * @property string|null $image
 * @property string|null $hash
 * @property string|null $description
 * @property string|null $price_offer
 * @property int $category_id
 * @property int $is_most_buy
 * @property int $is_active
 * @property Unit $unit
 * @property int|null $unit_value
 * @property-read Collection<int, Brand> $brands
 * @property-read int|null $brands_count
 * @property-read Collection<int, Category> $categories
 * @property-read int|null $categories_count
 * @property-read Collection<int, OrderDetails> $orderDetails
 * @property-read int|null $order_details_count
 * @method static ProductFactory factory($count = null, $state = [])
 * @method static Builder|Product hasName(?string $name)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCategoryId($value)
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHash($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereImage($value)
 * @method static Builder|Product whereIsActive($value)
 * @method static Builder|Product whereIsMostBuy($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product wherePriceOffer($value)
 * @method static Builder|Product whereUnit($value)
 * @method static Builder|Product whereUnitValue($value)
 * @method static Builder|Product whereUpdatedAt($value)
 * @property-read Collection<int, \CloudinaryLabs\CloudinaryLaravel\Model\Media> $medially
 * @property-read int|null $medially_count
 * @mixin Eloquent
 */
class Product extends Model
{
    protected $guarded = ['id'];

    use HasFactory, MediaAlly;


    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->BelongsToMany(Category::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    public function scopeHasName(Builder $query, ?string $name): void
    {
        Log::info('value of name is {name} ', ['name' => $name]);

        $query->where(
            'name',
            'LIKE',
            '%'.$name.'%'
        );
    }

    protected function unit(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => Unit::from($value),
            set: function (Unit $value) {
                return $value->value;
            }
        );
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: function (string $value) {

                Log::info('value {value} ', ['value' => $value]);

                if (! str_contains($value, '.')) {
                    return $value.'.'.'00';
                }
                $list = explode('.', $value);

                if (strlen($list[1] > 2)) {
                    $list[1] = substr($list[1], 0, 2);
                }

                while (strlen($list[1] < 2)) {
                    $list[1] = $list[1].'0';
                }

                Log::info('price after update {price} ', ['price' => $list[0].'.'.$list[1]]);

                return $list[0].'.'.$list[1];

            }
        );
    }
}
