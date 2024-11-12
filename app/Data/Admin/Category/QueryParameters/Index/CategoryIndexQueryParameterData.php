<?php

namespace App\Data\Admin\Category\QueryParameters\Index;

use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
use App\Transformers\MillesecondsToDateTransformer;
use Spatie\LaravelData\Attributes\WithTransformer;

class CategoryIndexQueryParameterData extends PaginationQueryParameterData
{
    public function __construct(
        ?int $page,
        ?int $perPage,
        public ?string $search,
        public ?string $sort,
        /** @var int[] */
        public array $ids = [],
        #[WithTransformer(MillesecondsToDateTransformer::class)]
        public array $date_range = [],
    ) {
        parent::__construct($page, $perPage);
    }
}
