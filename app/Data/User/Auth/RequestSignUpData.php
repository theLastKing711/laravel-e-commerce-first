<?php

namespace App\Data\User\Auth;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class RequestSignUpData extends Data
{
    public function __construct(
        #[OAT\Property(default: '963')]
        public string $dial_code,
        #[OAT\Property(default: '0968259851')]
        public string $number,
    ) {}
}
