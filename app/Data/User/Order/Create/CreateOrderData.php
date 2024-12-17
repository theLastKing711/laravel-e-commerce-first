<?php

namespace App\Data\User\Order\Create;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use App\Models\Product;
use App\Models\Setting;
use App\Rules\Coupon\Code\UnUsedCoupon\UnusedCoupon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Bail;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class CreateOrderData extends Data
{
    /** @param Collection<int, CreateOrderDetailsData> $order_details*/
    public function __construct(
        #[OAT\Property]
        public ?string $notice,
        #[
            DateProperty,
            Bail,
            Date,
            AfterOrEqual('+ 1 minute'),
        ]
        public string $required_time,
        #[
            OAT\Property(default: '123456'),
            Bail,
            Numeric,
            Digits(6),
            Exists('coupons', 'code'),
            UnusedCoupon
        ]
        public string $code,
        #[ArrayProperty(CreateOrderDetailsData::class)]
        public Collection $order_details,
    ) {}

    public static function rules()
    {
        // App::setLocale('ar');

        /** @var Setting $setting */
        $setting = Setting::first();

        $settings_min_item_per_order =
            $setting
                ->order_delivery_min_item_per_order;

        /** @var Collection<int, string> $active_products_Ids */
        $active_products_Ids =
            Product::query()
                ->whereIsActive(true)
                ->get()
                ->pluck('id');

        return [
            'order_details' => ['array', 'required'],
            'order_details.*.quantity' => 'min:'.$settings_min_item_per_order,
            'order_details.*.product_id' => Rule::in($active_products_Ids),
        ];
    }

    public static function messages()
    {
        return [
            'order_details.*.product_id.in' => __('validation.user.product.quantity.out_of_stock'),
        ];
    }
}

// UserOwnsCoupon commented attributes for $order_details.
