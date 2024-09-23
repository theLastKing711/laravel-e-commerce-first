<?php

namespace App\Data\Admin\Category\QueryParameters;

use App\Data\Shared\Swagger\Parameter\QueryParameter\ListQueryParameterRef;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameterRef;
use Illuminate\Support\Collection;
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
