<?php

namespace App\Data\Admin\Product;

use App\Data\Shared\Pagination\PaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class PaginatedProductData extends PaginationResultData
{
    public function __construct(
        int $current_page,
        string $per_page,
        #[ArrayProperty(ProductData::class)]
        /** @var Collection<int, ProductData> */
        public Collection $data,
        int $total
    ) {
        parent::__construct($current_page, $per_page, $total);
    }

}
