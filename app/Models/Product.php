<?php

namespace App\Models;

use App\Enum\Unit;
use App\Interfaces\Mediable;
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
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection as SupportCollection;
use Log;

use function explode;
use function str_contains;
use function strlen;

/**
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
 *
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
 *
 * @property-read Collection<int, \CloudinaryLabs\CloudinaryLaravel\Model\Media> $medially
 * @property-read int|null $medially_count
 * @property-read Collection<int, \App\Models\User> $favouritedByUsers
 * @property-read int|null $favourited_by_users_count
 * @property int $is_favourite
 * @property-read Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 *
 * @method static Builder|Product whereIsFavourite($value)
 *
 * @mixin Eloquent
 */
class Product extends Model implements Mediable
{
    protected $guarded = ['id'];

    use HasFactory, MediaAlly;

    public function medially(): MorphMany
    {
        return $this->morphMany(Media::class, 'medially');
    }

    public function brands(): BelongsToMany
    {
        return $this->belongsToMany(Brand::class);
    }

    public function favouritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favourite_product');
    }

    public function categories(): BelongsToMany
    {
        return $this->BelongsToMany(Category::class);
    }

    public function orderDetails(): HasMany
    {
        return $this->hasMany(OrderDetails::class);
    }

    /**
     * Get all of the variants for the Product
     */
    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function scopeHasName(Builder $query, ?string $name): void
    {

        $query->where(
            'name',
            'LIKE',
            '%'.$name.'%'
        );
    }

    // protected function unit(): Attribute
    // {
    //     return Attribute::make(
    //         get: fn (int $value) => Unit::from($value),
    //         set: function (Unit $value) {
    //             return $value->value;
    //         }
    //     );
    // }

    //int when saved to db, and enum when retrieved from database
    protected function casts(): array
    {
        return [
            'unit' => Unit::class,
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value,
            set: function (string $value) {

                // Log::info('value {value} ', ['value' => $value]);

                if (! str_contains($value, '.')) {
                    return $value.'.'.'00';
                }
                $list = explode('.', $value);

                if (strlen($list[1]) > 2) {
                    $list[1] = substr($list[1], 0, 2);
                }

                while (strlen($list[1]) < 2) {
                    $list[1] = $list[1].'0';
                }

                // Log::info('price after update {price} ', ['price' => $list[0].'.'.$list[1]]);

                return $list[0].'.'.$list[1];

            }
        );
    }

    public function getProductVariantByVariantValueId(int $variant_value_id): VariantValue
    {
        return $this
            ->variants
            ->pluck('variantValues')
            ->flatten()
            ->firstWhere('id', $variant_value_id)
            ->variant;
    }

    /**
       @return SupportCollection<int, int>
     */
    public function getVariantCombinationsIds(): SupportCollection
    {
        return
            $this
                ->getVariantCombinations()
                ->pluck('pivot.id');
    }

    /** @return SupportCollection<int, VariantValue> */
    public function getVariantCombinations(): SupportCollection
    {
        return $this
            ->variants
            ->pluck('variantValues')
            ->flatten()
            ->pluck('combinations')
            ->flatten();
    }

    public function hasThumbVariantCombination()
    {

        $product_variant_combinations =
            $this->getThumbVariantCombination();

        return $product_variant_combinations
            == null ? false : true;

    }

    public function getThumbVariantCombination(): ?VariantValue
    {
        return $this
            ->variants
            ->pluck('variantValues')
            ->flatten()
            ->pluck('combinations')
            ->flatten()
            ->firstWhere('pivot.is_thumb', true);
    }

    /** @return SupportCollection<int>  */
    public function getOtherVariantsVariantValueIdsByVariantValueId(int $variant_value_id): SupportCollection
    {
        $variant =
            $this
                ->variants
                ->pluck('variantValues')
                ->flatten()
                ->firstWhere('id', $variant_value_id)
                ->variant;

        $variant_value_variant_ids =
            $variant
                ->variantValues()
                ->pluck('id');

        return VariantValue::query()
            ->whereNotIn('id', $variant_value_variant_ids)
            ->whereHas('variant', function ($query) {
                $query->whereHas('product', function ($query) {
                    $query->id = $this;
                });
            })
            ->pluck('id');
    }

    public function hasThumbSecondVariantCombination(): bool
    {
        return $this
            ->getFirstThumbSecondVariantCombinationId()
            == null ? false : true;
    }

    public function setFirstVariantValueThumbToTrue(): void
    {
        $this
            ->variants
            ->pluck('variantValues')
            ->flatten()
            ->first()
            ?->update(['is_thumb' => true]);

    }

    public function setFirstVariantCombinationThumbToTrue(): void
    {
        $this
            ->getVariantCombinations()
            ->first()
            ?->update(['is_thumb' => true]);

    }

    public function setFirstSecondVariantCombinationThumbToTrue(): void
    {
        $this
            ->getSecondVariantCombinations()
            ->first()
            ?->update(['is_thumb' => true]);

    }

    public function getFirstThumbSecondVariantCombinationId(): ?int
    {
        return $this
            ->getSecondVariantCombinations()
            ->firstWhere('pivot.is_thumb', true)
            ?->id;
    }

    public function getFirstSecondVariantCombinationId(): ?int
    {
        return $this
            ->getSecondVariantCombinations()
            ->first()
            ?->id;
    }

    /** @return SupportCollection<int, VariantValue> */
    public function getSecondVariantCombinations(): SupportCollection
    {
        return $this
            ->variants
            ->pluck('variantValues')
            ->flatten()
            ->pluck('late_combinations')
            ->flatten();
    }

    public function getVariantsCount(): int
    {
        return $this->variants()->count();
    }

    public function hasThumbVariantValue(): bool
    {
        return $this->thumbVariantValue() == null ? false : true;
    }

    public function thumbVariantValue(): VariantValue
    {
        return
            $this
                ->variants
                ->pluck('variantValues')
                ->flatten()
                ->firstWhere('is_thumb', true);
    }

    public function hasOneVariant()
    {
        return $this->getVariantsCount() == 1;
    }

    public function hasTwoVariants(): bool
    {
        return $this->getVariantsCount() == 2;
    }

    public function hasThreeVariants(): bool
    {
        return $this->getVariantsCount() == 3;
    }
}
