<?php

namespace App\Data\Admin\Product;

use App\Models\Product;
use Illuminate\Validation\Rules\Exists;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[Oat\Schema(schema: 'adminProductList')]
class ProductListData extends Data
{
    public function __construct(
        #[
            OAT\Property(type: 'integer')
        ]
        public int $id,
        #[OAT\Property(type: 'string')]
        public string $name,
    ) {
    }
}
