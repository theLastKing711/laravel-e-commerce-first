<?php

namespace App\Data\Admin\Coupon;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\After;
use Spatie\LaravelData\Attributes\Validation\AfterOrEqual;
use Spatie\LaravelData\Attributes\Validation\Date;
use Spatie\LaravelData\Attributes\Validation\Digits;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Numeric;
use Spatie\LaravelData\Attributes\Validation\RequiredWithout;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class UpdateCouponData extends Data
{
    public function __construct(
        #[
            OAT\Property(),
        ]
        public string $name,
        #[
            OAT\Property(),
            Digits(6)
        ]
        public string $code,
        #[
            OAT\Property(),
            Unique('coupons', 'code'),
            Min(5),
            Max(90),
            Numeric,
            RequiredWithout('value')
        ]
        public ?string $percent,
        #[
            OAT\Property(),
            RequiredWithout('percent')
        ]
        public ?int $value,
        #[
            DateProperty(default: '2024-08-02 18:31:45'),
            Date,
            AfterOrEqual('- 5 minutes'),
        ]
        public string $start_at,
        #[
            DateProperty('2024-09-02 18:31:45'),
            Date,
            After('start_at'),
        ]
        public string $end_at,
        #[ArrayProperty]
        /** @var Collection<int, int> */
        public array $user_ids,
        #[ArrayProperty]
        /** @var Collection<int, int> */
        public array $group_ids,
    ) {
    }
}
