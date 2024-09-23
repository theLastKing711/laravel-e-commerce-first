<?php

namespace App\Data\Admin\Driver;

use App\Data\Shared\Swagger\Property\DateProperty;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class DriverData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public ?string $name,
        #[OAT\Property()]
        public ?string $username,
        #[
            OAT\Property(),
        ]
        public ?string $number,
        #[DateProperty]
        public string $created_at,
    ) {
    }

}
