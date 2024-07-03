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


namespace App\Models{
/**
 * 
 *
 * @property int $id
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
	class Brand extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $hash
 * @property int|null $is_special
 * @property int|null $parent_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Category> $children
 * @property-read int|null $children_count
 * @property-read Category|null $parent
 * @method static \Database\Factories\CategoryFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Category newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Category query()
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereIsSpecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereParentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereUpdatedAt($value)
 * @property string|null $image
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Product> $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Category whereImage($value)
 * @mixin \Eloquent
 */
	class Category extends \Eloquent {}
}

namespace App\Models{
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
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
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
 * @mixin \Eloquent
 */
	class OrderDetails extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\OrderItemFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string $name
 * @property string $price
 * @property string|null $image
 * @property string|null $hash
 * @property string|null $description
 * @property string|null $price_offer
 * @property int $category_id
 * @property int $is_most_buy
 * @property int $is_active
 * @property string|null $unit
 * @property int|null $unit_value
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Brand> $brands
 * @property-read int|null $brands_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Category> $categories
 * @property-read int|null $categories_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\OrderDetails> $orderDetails
 * @property-read int|null $order_details_count
 * @method static \Database\Factories\ProductFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereHash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereIsMostBuy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product wherePriceOffer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUnitValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Product whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Product extends \Eloquent {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @property string|null $gender
 * @property string|null $number
 * @property string|null $dial_code
 * @property string|null $account_registration_step
 * @property string|null $code
 * @property string|null $temp_number
 * @property string|null $temp_dial_code
 * @property string|null $temp_code
 * @property float|null $lat
 * @property float|null $lon
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null, $without = false)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereAccountRegistrationStep($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDialCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTempCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTempDialCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTempNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutPermission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User withoutRole($roles, $guard = null)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

