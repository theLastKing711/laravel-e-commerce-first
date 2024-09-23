<?php

namespace App\Data\Shared\Pagination;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminPaginatedAbstract')]
class PaginationResultData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $current_page,
        #[OAT\Property()]
        public int $per_page,
        #[OAT\Property()]
        public int $total,
    ) {
    }

}
