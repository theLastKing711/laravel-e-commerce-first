<?php

namespace App\Data\Admin\Group;

use App\Enum\Gender;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\DigitsBetween;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class GroupData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public ?int $id,
        #[OAT\Property()]
        public ?string $name,
        #[OAT\Property(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        )]
        public string $created_at,
    ) {
    }

}
