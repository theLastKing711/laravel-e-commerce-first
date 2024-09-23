<?php

namespace App\Data\Admin\Coupon;

use App\Services\FileService;
use App\Transformers\ToWebStoragePathTransformer;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class CouponUserData extends Data
{
    public function __construct(
        #[
            OAT\Property(),
        ]
        public string $id,
        #[
            OAT\Property(),
        ]
        public string $name,
        #[
            OAT\Property(),
        ]
        public string $number,
    ) {
    }

}
