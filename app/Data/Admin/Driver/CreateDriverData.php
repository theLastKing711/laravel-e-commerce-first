<?php

namespace App\Data\Admin\Driver;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCreateDriverData')]
class CreateDriverData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string')]
        public ?string $name,
        #[OAT\Property(type: 'string')]
        public string $username,
        #[
            OAT\Property(type: 'string'),
        ]
        public ?string $password,
        #[
            OAT\Property(type: 'string'),
            DigitsBetween(min: 10, max: 10)
        ]
        public ?string $number,
    ) {
    }

}
