<?php

namespace App\Data\Shared\Pagination\QueryParameters;

use Spatie\LaravelData\Data;

class PaginationQueryParameterData extends Data
{
    public function __construct(
        public ?int $page,
        public ?int $perPage,
    ) {
    }
}


