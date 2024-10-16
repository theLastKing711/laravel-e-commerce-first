<?php

namespace App\Data\User\Category\Index;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[OAT\Schema()]
class ParentListData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[OAT\Property()]
        public ?int $parent_id,
        #[OAT\Property()]
        public string $name,
    ) {
    }
}
