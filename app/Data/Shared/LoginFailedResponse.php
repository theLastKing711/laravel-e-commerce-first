<?php

namespace App\Data\Shared;
use Spatie\LaravelData\Data;
use OpenApi\Attributes as OAT;

#[Oat\Schema()]
class LoginFailedResponse extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $message,
    ) {
    }
}
