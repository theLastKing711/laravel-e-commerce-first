<?php

namespace App\Data\Shared\Pagination;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminCursorPaginatedAbstract')]
abstract class CursorPaginationResultData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public int $per_page,
        #[OAT\Property()]
        public ?string $next_cursor,
    ) {
    }
}
