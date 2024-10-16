<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\Swagger\Property\DateProperty;
use OpenApi\Attributes as Oat;
use Spatie\LaravelData\Data;

#[Oat\Schema()]
class TestCategoryData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int  $id,
        #[OAT\Property()]
        public ?int $parent_id,
        #[OAT\Property()]
        public ?string $parent_name,
        #[OAT\Property()]
        public string  $name,
        #[DateProperty]
        public string  $created_at,
        //        #[OAT\Property(default: 'type of containing type')]
        //        public ?CategoryData $parent,
    )
    {
    }

}
