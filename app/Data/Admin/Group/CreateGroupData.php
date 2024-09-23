<?php

namespace App\Data\Admin\Group;


use App\Enum\Gender;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[Oat\Schema()]
class CreateGroupData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public ?string $name,
    ) {
    }

}
