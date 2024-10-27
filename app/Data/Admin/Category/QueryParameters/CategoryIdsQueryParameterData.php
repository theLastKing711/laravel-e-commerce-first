<?php

namespace App\Data\Admin\Category\QueryParameters;

use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Data;

class CategoryIdsQueryParameterData extends Data
{
    public function __construct(
        public ?string $name,
        #[
            Exists('categories', 'id')
        ]
        /** @var Collection<int, int> */
        public array $ids = [],
    ) {
    }
}
