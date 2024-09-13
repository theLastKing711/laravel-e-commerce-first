<?php

namespace App\Data\Shared;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;

#[Oat\Schema(schema: 'LoginResponse')]
class LoginFailedResponse extends Data
{
    public function __construct(
        #[OAT\Property(type: 'string')]
        public string $message,
    ) {
    }
}
