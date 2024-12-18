<?php

namespace App\Models;

use App\Enum\Unit;
use App\Interfaces\Mediable;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection as SupportCollection;
use Log;

use function explode;
use function str_contains;
use function strlen;

/**
 * @property string $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $price
 * @property string|null $hash
 * @property string|null $description
 * @property string|null $price_offer
 * @property int $is_most_buy
 * @property int $is_favourite
 * @property int $is_active
 * @property Unit|null $unit
 * @property int|null $unit_value
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\Brand,\Illuminate\Database\Eloquent\Relations\Pivot> $brands
 * @property-read int|null $brands_count
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\Category,\Illuminate\Database\Eloquent\Relations\Pivot> $categories
 * @property-read int|null $categories_count
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\User,\Illuminate\Database\Eloquent\Relations\Pivot> $favouritedByUsers
 * @property-read int|null $favourited_by_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medially
 * @property-read int|null $medially_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetails> $orderDetails
 * @property-read int|null $order_details_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Variant> $variants
 * @property-read int|null $variants_count
 *
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static Builder|Product hasName(?string $name)
 * @method static Builder|Product newModelQuery()
 * @method static Builder|Product newQuery()
 * @method static Builder|Product query()
 * @method static Builder|Product whereCreatedAt($value)
 * @method static Builder|Product whereDescription($value)
 * @method static Builder|Product whereHash($value)
 * @method static Builder|Product whereId($value)
 * @method static Builder|Product whereIsActive($value)
 * @method static Builder|Product whereIsFavourite($value)
 * @method static Builder|Product whereIsMostBuy($value)
 * @method static Builder|Product whereName($value)
 * @method static Builder|Product wherePrice($value)
 * @method static Builder|Product wherePriceOffer($value)
 * @method static Builder|Product whereUnit($value)
 * @method static Builder|Product whereUnitValue($value)
 * @method static Builder|Product whereUpdatedAt($value)
 *
 * @mixin Eloquent
 */
class Product extends Eloquent implements Mediable
{
    protected $guarded = ['id'];

    use HasFactory, HasUlids, MediaAlly;

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
       @return SupportCollection<int, string>
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

    public function hasThumbVariantCombinedBy()
    {

        $product_variant_combined_by =
            $this->getThumbVariantCombinedBy();

        return $product_variant_combined_by
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

    public function getThumbVariantCombinedBy(): ?VariantValue
    {
        return $this
            ->variants
            ->pluck('variantValues')
            ->flatten()
            ->pluck('combined_by')
            ->flatten()
            ->firstWhere('pivot.is_thumb', true);
    }

    /** @return SupportCollection<int, string>  */
    public function getSecondVariantVariantValuesByFirstVariantValue(VariantValue $first_variant_value): SupportCollection
    {

        /** @var SupportCollection<int, string> $product_second_variant_variant_values_ids */
        $product_second_variant_variant_values_ids =
            $this
                ->variants
                ->whereNot('id', $first_variant_value->variant_id)
                ->first()
                ->variantValues
                ->pluck('id');

        return $product_second_variant_variant_values_ids;
    }

    /** @return SupportCollection<int, string>  */
    public function getFirstVariantVariantValuesIds(): SupportCollection
    {

        /** @var Variant $product_first_variant */
        $product_first_variant =
            $this
                ->variants
                ->first();

        /** @var SupportCollection<int, string> $product_first_variant_variant_values_ids */
        $product_first_variant_variant_values_ids =
            $product_first_variant
                ->variantValues
                ->pluck('id');

        return $product_first_variant_variant_values_ids;
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

    public function getFirstThumbSecondVariantCombinationId(): ?string
    {
        return $this
            ->getSecondVariantCombinations()
            ->firstWhere('pivot.is_thumb', true)
            ?->id;
    }

    public function getFirstSecondVariantCombinationId(): ?string
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

    public function thumbVariantValue(): ?VariantValue
    {
        return
            $this
                ->variants
                ->pluck('variantValues')
                ->flatten()
                ->firstWhere('is_thumb', true);
    }

    public function hasNoVariants()
    {
        return $this->getVariantsCount() == 0;
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
