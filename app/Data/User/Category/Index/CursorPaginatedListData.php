<?php

namespace App\Data\User\Category\Index;

use App\Data\Shared\Pagination\CursorPaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema()]
class CursorPaginatedListData extends CursorPaginationResultData
{
    public function __construct(
        int $per_page,
        ?string $next_cursor,
        #[ArrayProperty(ParentListData::class)]
        /** @var Collection<int, ParentListData> */
        public Collection $data,
    ) {
        parent::__construct($per_page, $next_cursor);
    }
}
