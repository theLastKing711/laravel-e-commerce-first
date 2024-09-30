<?php

namespace App\Data\Admin\Category\QueryParameters\Index;

use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
use App\Transformers\MillesecondsToDateTransformer;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Attributes\WithTransformer;

class CategoryIndexQueryParameterData extends PaginationQueryParameterData
{
    public function __construct(
        ?int $page,
        ?int $perPage,
        public ?string $search,
        /** @var Collection <int, int> */
        public ?string $sort,
        public array $ids = [],
        #[WithTransformer(MillesecondsToDateTransformer::class)]
        public array $date_range = [],
    ) {
        parent::__construct($page, $perPage);
    }
}
