<?php

namespace App\Data\Admin\Product;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript]
#[Oat\Schema(schema: 'adminProductList')]
class ProductListData extends Data
{
    public function __construct(
        #[OAT\Property()]
        public string $id,
        #[OAT\Property()]
        public string $name,
    ) {
    }
}
