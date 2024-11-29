<?php

namespace App\Data\User\Home;

use App\Data\Shared\Pagination\CursorPaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema()]
class CursorPaginatedSearchSuggestionData extends CursorPaginationResultData
{
    /** @param Collection<int, ProductSearchSuggestionData> $data*/
    public function __construct(
        int $per_page,
        ?string $next_cursor,
        #[ArrayProperty(ProductSearchSuggestionData::class)]
        public Collection $data,
    ) {
        parent::__construct($per_page, $next_cursor);
    }
}
