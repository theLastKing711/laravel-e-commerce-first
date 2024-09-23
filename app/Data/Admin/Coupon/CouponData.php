<?php

namespace App\Data\Admin\Coupon;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Data\Shared\Swagger\Property\DateProperty;
use App\Models\Coupon;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class CouponData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $id,
        #[OAT\Property()]
        public string $name,
        #[OAT\Property()]
        public string $code,
        #[OAT\Property()]
        public ?string $percent,
        #[OAT\Property()]
        public ?string $value,
        #[DateProperty(default: '2024-08-02 18:31:45')]
        public string $start_at,
        #[DateProperty]
        public string $end_at,
        #[DateProperty]
        public string $created_at,
        /** @var Collection<int, CouponUserData> */
        #[ArrayProperty]
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
