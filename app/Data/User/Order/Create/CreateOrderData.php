<?php

namespace App\Data\User\Order\Create;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use App\Rules\Coupon\Code\UnUsedCoupon\UnusedCoupon;
use Illuminate\Support\Collection;
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
        #[
            OAT\Property(),
        ]
        public ?string $notice,
        #[DateProperty(),
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
}

// UserOwnsCoupon commented attributes for $order_details.
