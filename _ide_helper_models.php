<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Data\Shared{
/**
 *
 *
 * @template Models
 * @extends Models
 * @method static \Illuminate\Database\Eloquent\Builder|ModelWithouPivot newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelWithouPivot newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ModelWithouPivot query()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperModelWithouPivot {}
}

namespace App\Models{
/**
 *
 *
 * @property string $id
 * @property string $name
 * @property string|null $image
 * @property string|null $hash
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\BrandFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Brand whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperBrand {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $hash
 * @property int|null $is_special
 * @property string|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medially
 * @property-read int|null $medially_count
 * @property-read Category|null $parent
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\Product,\Illuminate\Database\Eloquent\Relations\Pivot> $products
 * @property-read int|null $products_count
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static Builder|Category hasParents(array $ids)
 * @method static Builder|Category isChild()
 * @method static Builder|Category isParent()
 * @method static Builder|Category latest()
 * @method static Builder|Category newModelQuery()
 * @method static Builder|Category newQuery()
 * @method static Builder|Category query()
 * @method static Builder|Category whereCreatedAt($value)
 * @method static Builder|Category whereHash($value)
 * @method static Builder|Category whereId($value)
 * @method static Builder|Category whereIsSpecial($value)
 * @method static Builder|Category whereName($value)
 * @method static Builder|Category whereParentId($value)
 * @method static Builder|Category whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCategory {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $code
 * @property string|null $percent
 * @property string|null $value
 * @property string $start_at
 * @property string $end_at
 * @method static \Database\Factories\CouponFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon query()
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereEndAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon wherePercent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereStartAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Coupon whereValue($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperCoupon {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, User> $users
 * @property-read int|null $users_count
 * @method static \Database\Factories\GroupFactory factory($count = null, $state = [])
 * @method static Builder|Group newModelQuery()
 * @method static Builder|Group newQuery()
 * @method static Builder|Group query()
 * @method static Builder|Group whereCreatedAt($value)
 * @method static Builder|Group whereId($value)
 * @method static Builder|Group whereName($value)
 * @method static Builder|Group whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperGroup {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string $medially_type
 * @property int $medially_id
 * @property string $file_url
 * @property string $file_name
 * @property string|null $file_type
 * @property int $size
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\MediaFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Media newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Media query()
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereFileUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMediallyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereMediallyType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Media whereUpdatedAt($value)
 * @property-read Model|\Eloquent $medially
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperMedia {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property int|null $from_admin
 * @property string $name
 * @property string $body
 * @property int $order_id
 * @property int $order_status
 * @property string $type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
 * @property-read int|null $user_count
 * @method static \Database\Factories\NotificationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification query()
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereFromAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereOrderStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Notification whereUpdatedAt($value)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperNotification {}
}

namespace App\Models{
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperOrder {}
}

namespace App\Models{
/**
 *
 *
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
 * @mixin Eloquent
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperOrderDetails {}
}

namespace App\Models{
/**
 *
 *
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
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperProduct {}
}

namespace App\Models{
/**
 *
 *
 * @property string $id
 * @property string $variant_combination_id
 * @property string $variant_value_id
 * @property int $is_thumb
 * @property string|null $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\VariantCombination|null $variantCombination
 * @property-read \App\Models\VariantValue|null $variantValue
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereIsThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereVariantCombinationId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecondVariantCombination whereVariantValueId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSecondVariantCombination {}
}

namespace App\Models{
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
 */
	#[\AllowDynamicProperties]
	class IdeHelperSetting {}
}

namespace App\Models{
/**
 *
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed|null $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property Gender $gender
 * @property string|null $number
 * @property string|null $dial_code
 * @property string|null $account_registration_step
 * @property string|null $code
 * @property string|null $temp_number
 * @property string|null $temp_dial_code
 * @property string|null $temp_code
 * @property string|null $username
 * @property float|null $lat
 * @property float|null $lon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Coupon> $coupons
 * @property-read int|null $coupons_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $driver_orders
 * @property-read int|null $driver_orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Group> $groups
 * @property-read int|null $groups_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Notification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static Builder|User like(string $column, string $value)
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User permission($permissions, $without = false)
 * @method static Builder|User query()
 * @method static Builder|User role($roles, $guard = null, $without = false)
 * @method static Builder|User whereAccountRegistrationStep($value)
 * @method static Builder|User whereCode($value)
 * @method static Builder|User whereCreatedAt($value)
 * @method static Builder|User whereDialCode($value)
 * @method static Builder|User whereEmail($value)
 * @method static Builder|User whereEmailVerifiedAt($value)
 * @method static Builder|User whereGender($value)
 * @method static Builder|User whereId($value)
 * @method static Builder|User whereLat($value)
 * @method static Builder|User whereLon($value)
 * @method static Builder|User whereName($value)
 * @method static Builder|User whereNumber($value)
 * @method static Builder|User wherePassword($value)
 * @method static Builder|User whereRememberToken($value)
 * @method static Builder|User whereTempCode($value)
 * @method static Builder|User whereTempDialCode($value)
 * @method static Builder|User whereTempNumber($value)
 * @method static Builder|User whereUpdatedAt($value)
 * @method static Builder|User whereUsername($value)
 * @method static Builder|User withoutPermission($permissions)
 * @method static Builder|User withoutRole($roles, $guard = null)
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $favouriteProducts
 * @property-read int|null $favourite_products_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static Builder|User isUser()
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

namespace App\Models{
/**
 *
 *
 * @property string $id
 * @property string $product_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantCombination> $combinations
 * @property-read int|null $combinations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medially
 * @property-read int|null $medially_count
 * @property-read \App\Models\Product $product
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\VariantValue> $variantValues
 * @property-read int|null $variant_values_count
 * @method static \Database\Factories\VariantFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant query()
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Variant whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVariant {}
}

namespace App\Models{
/**
 *
 *
 * @property string $id
 * @property string $first_variant_value_id
 * @property string $second_variant_value_id
 * @property int $is_thumb
 * @property string|null $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantValue,\App\Models\SecondVariantCombination> $combinations
 * @property-read int|null $combinations_count
 * @property-read \App\Models\VariantValue|null $first_variant_value
 * @property-read \App\Models\VariantValue|null $second_variant_value
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination query()
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereAvailable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereFirstVariantValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereIsThumb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereSecondVariantValueId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|VariantCombination whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVariantCombination {}
}

namespace App\Models{
/**
 *
 *
 * @property string $id
 * @property string $variant_id
 * @property int $is_thumb
 * @property string $name
 * @property string $price
 * @property int $available
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantValue,\App\Models\VariantCombination> $combinations
 * @property-read int|null $combinations_count
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantValue,\App\Models\VariantCombination> $combined_by
 * @property-read int|null $combined_by_count
 * @property-read \App\Data\Shared\ModelwithPivotCollection<\App\Models\VariantCombination,\App\Models\SecondVariantCombination> $late_combinations
 * @property-read int|null $late_combinations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Media> $medially
 * @property-read int|null $medially_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SecondVariantCombination> $second_level_combinations
 * @property-read int|null $second_level_combinations_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SecondVariantCombination> $second_level_combined_by
 * @property-read int|null $second_level_combined_by_count
 * @property-read \App\Models\Variant $variant
 * @method static \Database\Factories\VariantValueFactory factory($count = null, $state = [])
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue newModelQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue newQuery()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue query()
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereAvailable($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereCreatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereId($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereIsThumb($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereName($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue wherePrice($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereUpdatedAt($value)
 * @method static \AjCastro\EagerLoadPivotRelations\EagerLoadPivotBuilder|VariantValue whereVariantId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperVariantValue {}
}

namespace App\Models{
/**
 *
 *
 * @property Day $name
 * @method static \Database\Factories\WorkDaysFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays query()
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $number
 * @property int $is_vacation
 * @property string $from
 * @property string $to
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereIsVacation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WorkDays whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperWorkDays {}
}

