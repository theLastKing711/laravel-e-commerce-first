<?php

namespace App\Http\Controllers\User\Product;

use App\Data\Admin\User\Product\GetProductDetailsData;
use App\Data\Admin\User\Product\ProductIdData;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/user/products/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/userProductIdPathParameter',
            ),
        ],
    ),
]
class GetProductDetailsController extends Controller
{
    #[OAT\Get(path: '/user/products/{id}', tags: ['userProducts'])]
    #[SuccessItemResponse(GetProductDetailsData::class)]
    public function __invoke(ProductIdData $productIdData)
    {
        $product_id = $productIdData->id;

        $product = Product::query()
            ->select('id', 'name', 'price')
            ->whereId($product_id)
            ->selectRaw(
                '
                case
                when (
                    select count(*)
                    from user_favourite_product
                    where exists (
                            select true
                            where user_favourite_product.product_id = products.id
                            and user_favourite_product.user_id = ?
                        )
                ) >= 1
                then 1
                else 0
                end as is_favourite',
                [21]
            )
            ->with(
                [
                    'medially' => function ($query) {
                        $query->select('medially_id', 'file_url')->first();
                    },
                    'variants' => [
                        'medially:medially_id,file_url',
                        'variantValues' => [
                            'medially:medially_id,file_url',
                        ],
                    ],
                ]
            )
            ->first();

        return GetProductDetailsData::from($product);

    }
}
