<?php

namespace App\Data\Admin\Driver;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class CreateDriverData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public ?string $name,
        #[OAT\Property()]
        public string $username,
        #[
            OAT\Property(),
        ]
        public ?string $password,
        #[
            OAT\Property(),
            DigitsBetween(min: 10, max: 10)
        ]
        public ?string $number,
    ) {
    }

}
