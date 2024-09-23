<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\Pagination\PaginationResultData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;


#[Oat\Schema()]
class PaginatedCategoryData extends PaginationResultData
{
    public function __construct(
        int $current_page,
        int $per_page,
        #[ArrayProperty(CategoryData::class)]
        /** @var Collection<int, CategoryData> */
        public Collection $data,
        int $total
    ) {
        parent::__construct($current_page, $per_page, $total);
    }
}
