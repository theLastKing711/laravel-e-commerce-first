<?php

namespace App\Data\User\Order\Create;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Rules\Coupon\Code\UnUsedCoupon\UnusedCoupon;
use App\Rules\Coupon\Code\UserOwnsCoupon\UserOwnsCoupon;
use App\Rules\Product\ActiveProduct\ActiveProduct;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Bail;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\DateEquals;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class CreateOrderData extends Data
{
    public function __construct(
        #[
            OAT\Property(),
        ]
        public ?string $notice,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2024-09-11 18:31:45',
            pattern: 'YYYY-MM-DD'
        ),
            Bail,
            Date,
            AfterOrEqual('+ 1 minute'),
            ActiveProduct
        ]
        public string $required_time,
        #[
            OAT\Property(default: '123456'),
            Bail,
            Numeric,
            Digits(6),
            Exists('coupons', 'code'),
            UserOwnsCoupon,
            UnusedCoupon
        ]
        public string $code,
        #[ArrayProperty]
        /** @var Collection<int, CreateOrderDetailsData> */
        public Collection $order_details,
    ) {
    }
}
