<?php

namespace App\Data\Admin\Stats;


use App\Data\Shared\Swagger\Property\DateProperty;
use App\Enum\Gender;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema()]
class BestSellingProductsQueryParameterData extends Data
{
    public function __construct(
        #[DateProperty]
        public ?string $start_at,
        #[DateProperty]
        public ?string $end_at,
    ) {
    }

}
