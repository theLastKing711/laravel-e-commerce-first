<?php

namespace App\Http\Controllers\User\Product;

use App\Data\Admin\User\Product\Details\GetProductDetailsData;
use App\Data\Admin\User\Product\ProductIdData;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\Debugbar\Facades\Debugbar;
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
        Debugbar::info($productIdData);

        $product_id = $productIdData->id;

        $product_variants_count = Product::query()
            ->select('id')
            ->whereRelation(
                'variants.variantValues',
                'variant_values.id',
                $product_id
            )
            ->withCount('variants')
            ->first()
            ->variants_count;

        $is_product_with_one_variant =
            $product_variants_count == 1;

        if ($is_product_with_one_variant) {

            Debugbar::info('product with one variant');

            $product = Product::query()
                ->select('id', 'name', 'price')
                ->with('variants.variantValues')
                ->whereRelation(
                    'variants.variantValues',
                    'variant_values.id',
                    $product_id
                )
                ->first();

            return GetProductDetailsData::from($product, $product_id);
        }

        $is_product_with_two_variants =
            $product_variants_count == 2;

        if ($is_product_with_two_variants) {

            Debugbar::info('product with two variant');

            $product = Product::query()
                ->select('id', 'name', 'price')
                ->with('variants.variantValues.combinations')
                ->whereRelation(
                    'variants.variantValues.combinations',
                    'variant_combination.id',
                    $product_id
                )
                ->first();

            return GetProductDetailsData::from($product, $product_id);
        }

        $is_product_with_three_variants =
            $product_variants_count == 3;

        if ($is_product_with_three_variants) {

            Debugbar::info('product with three variant');

            $product = Product::query()
                ->join(
                    'variants',
                    'variants.id',
                    '=',
                    'products.id',
                )
                ->join(
                    'variant_values',
                    'variant_values.id',
                    '=',
                    'variants.id',
                )
                ->join(
                    'variant_combination',
                    'variant_combination.first_variant_value_id',
                    '=',
                    'variant_values.id',
                )
                ->join(
                    'second_variant_combination',
                    'second_variant_combination.variant_combination_id',
                    '=',
                    'variant_values.id',
                )
                ->select([
                    'products.id',
                    'products.name',
                    'products.price',
                ])
                ->with('variants.variantValues.combinations.combinations')
                ->firstWhere('second_variant_combination.id', $product_id);

            return $product;

            return GetProductDetailsData::from($product, $product_id);
        }

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
            ->first();

        return GetProductDetailsData::from($product, $product_id);

    }
}
