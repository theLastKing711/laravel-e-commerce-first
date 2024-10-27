<?php

namespace App\Data\Admin\User\Product;

use App\Data\Shared\Pagination\CursorPaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()]
#[Oat\Schema()]
class CursorPaginatedFavouriteProductData extends CursorPaginationResultData
{
    public function __construct(
        int $per_page,
        ?string $next_cursor,
        #[ArrayProperty(GetUserFavouriteProductsData::class)]
        /** @var Collection<int, GetUserFavouriteProductsData> */
        public Collection $data,
    ) {
        parent::__construct($per_page, $next_cursor);
    }
}
