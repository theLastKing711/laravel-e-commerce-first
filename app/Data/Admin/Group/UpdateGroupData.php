<?php

namespace App\Data\Admin\Group;

use App\Enum\Gender;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class UpdateGroupData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public ?string $name,
    ) {
    }
}
