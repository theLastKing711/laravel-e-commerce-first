<?php

namespace App\Data\Admin\Category\QueryParameters\Index;

use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
use Illuminate\Support\Collection;

class CategoryIndexQueryParameterData extends PaginationQueryParameterData
{
    public function __construct(
        ?int $page,
        ?int $perPage,
        public ?string $search,
        /** @var Collection <int, int> */
        public ?string $sort,
        public array $ids = [],
    ) {
        parent::__construct($page, $perPage);
    }
}
