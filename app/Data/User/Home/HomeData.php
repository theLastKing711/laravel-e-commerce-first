<?php

namespace App\Data\User\Home;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use Illuminate\Support\Collection;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema()]
class HomeData extends Data
{
    public function __construct(
        #[ArrayProperty(AllCategoriesData::class)]
        /** @var Collection<int, HomeProductListData> */
        public Collection $user_purchased_products,
        #[ArrayProperty(AllCategoriesData::class)]
        /** @var Collection<int, AllCategoriesData> */
        public Collection $categories,
        #[ArrayProperty(AllCategoriesData::class)]
        /** @var Collection<int, HomeProductListData> */
        public Collection $most_selling_products,
    ) {
    }
}
