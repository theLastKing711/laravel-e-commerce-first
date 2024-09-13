<?php

namespace App\Data\Admin\Coupon;

use App\Models\Coupon;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCoupon')]
class CouponData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer'),
        ]
        public string $id,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $name,
        #[
            OAT\Property(type: 'string'),
        ]
        public string $code,
        #[
            OAT\Property(type: 'string'),
        ]
        public ?string $percent,
        #[
            OAT\Property(type: 'string'),
        ]
        public ?string $value,
        #[
            OAT\Property(
                type: 'string',
                format: 'datetime',
                default: '2024-08-02 18:31:45',
                pattern: 'YYYY-MM-DD'
            ),
        ]
        public string $start_at,
        #[
            OAT\Property(
                type: 'string',
                format: 'datetime',
                default: '2014-09-02 18:31:45',
                pattern: 'YYYY-MM-DD'
            ),
        ]
        public string $end_at,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
        /** @var Collection<int, CouponUserData> */
        #[OAT\Property(
            type: 'array',
            items: new OAT\Items(
                type: CouponUserData::class,
            )
        )]
        public Collection $users,
    ) {
    }

    public static function fromModel(Coupon $coupon): self
    {
        return new self(
            id: $coupon->id,
            name: $coupon->name,
            code: $coupon->code,
            percent: $coupon->percent,
            value: $coupon->value,
            start_at: $coupon->start_at,
            end_at: $coupon->end_at,
            created_at: $coupon->created_at,
            users: CouponUserData::collect($coupon->users)
        );
    }
}
