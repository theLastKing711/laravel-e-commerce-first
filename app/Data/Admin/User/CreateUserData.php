<?php

namespace App\Data\Admin\User;


use App\Enum\Gender;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema(schema: 'adminCreateUser')]
class CreateUserData extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string')]
        public ?string $name,
        #[OAT\Property(type: 'string')]
        public string $dial_code,
        #[
            OAT\Property(),
        ]
        public Gender $gender,
        #[
            OAT\Property(type: 'string'),
            DigitsBetween(min: 10, max: 10)
        ]
        public ?string $number,
    ) {
    }

}