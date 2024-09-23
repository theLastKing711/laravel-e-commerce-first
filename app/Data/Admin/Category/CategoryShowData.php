<?php

namespace App\Data\Admin\Category;

use App\Data\Shared\ListData;
use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class CategoryShowData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public ?CategoryData $data,
        #[ArrayProperty(ListData::class)]
        /** @var Collection<int, ListData> */
        public Collection $items,
    ) {
    }

}

