<?php

namespace App\Data\Admin\User;

use App\Enum\Gender;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminUser')]
class UserData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public ?int $id,
        #[OAT\Property()]
        public ?string $name,
        #[OAT\Property()]
        public string $dial_code,
        #[
            OAT\Property(),
        ]
        public Gender $gender,
        #[
            OAT\Property(),
            DigitsBetween(min: 10, max: 10)
        ]
        public ?string $number,
    ) {
    }

}
