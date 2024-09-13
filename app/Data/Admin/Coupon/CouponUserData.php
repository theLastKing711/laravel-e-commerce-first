<?php

namespace App\Data\Admin\Coupon;

use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCouponUser')]
class CouponUserData extends Data
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
        public string $number,
    ) {
    }

}
