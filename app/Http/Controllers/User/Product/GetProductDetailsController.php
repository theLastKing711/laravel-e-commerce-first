<?php

namespace App\Http\Controllers\User\Product;

use App\Data\Admin\User\Product\Details\GetProductDetailsData;
use App\Data\Admin\User\Product\Details\GetProductDetailsRequestData;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
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
                ref: '#/components/parameters/userProductVariationtIdPathParameter',
                schema: new OAT\Schema(
                    type: 'string',
                ),
            ),
        ],
    ),
]
class GetProductDetailsController extends Controller
{
    #[OAT\Get(path: '/user/products/{id}', tags: ['userProducts'])]
    #[QueryParameter('first_variant_value_id')]
    #[QueryParameter('second_variant_value_id')]
    #[QueryParameter('third_variant_value_id')]
    #[SuccessItemResponse(GetProductDetailsData::class)]
    public function __invoke(GetProductDetailsRequestData $request)
    {

        Debugbar::info($request);

        $product_id = $request->id;

        $product_variants_count = Product::query()
            ->whereId($request->id)
            ->withCount('variants')
            ->variants_count;

        // $product = Product::query()
        //     ->join(
        //         'variants',
        //         'variants.product_id',
        //         '=',
        //         'products.id',
        //     )
        //     ->join(
        //         'variant_values',
        //         'variant_values.variant_id',
        //         '=',
        //         'variants.id',
        //     )
        //     ->join(
        //         'variant_combination',
        //         'variant_combination.first_variant_value_id',
        //         '=',
        //         'variant_values.id',
        //     )
        //     ->join(
        //         'second_variant_combination',
        //         'second_variant_combination.variant_combination_id',
        //         '=',
        //         'variant_combination.id',
        //     )
        //     ->select([
        //         'products.id',
        //         'products.name',
        //         'products.price',
        //     ])
        //     ->with('variants.variantValues.combinations.combinations')
        //     ->where('second_variant_combination.id', $product_id)
        //     ->orWhere('variant_combination.id', $product_id)
        //     ->orWhere('variant_values.id', $product_id)
        //     ->first();

        $product_has_one_variant = $product_variants_count == 1;

        if ($product_has_one_variant) {

            $one_variant_product = Product::query()
                ->join(
                    'variants',
                    'variants.product_id',
                    '=',
                    'products.id',
                )
                ->join(
                    'variant_values',
                    'variant_values.variant_id',
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
                    'variant_combination.id',
                )
                ->select([
                    'products.id',
                    'products.name',
                    'products.price',
                ])
                // ->selectRaw('(select ')
                ->with('variants.variantValues')
                ->first();

            Debugbar::info('product with one variant');

            return GetProductDetailsData::from($one_variant_product, $product_id);
        }

        $product_has_two_variants =
            $product_variants_count == 2;

        if ($product_has_two_variants) {

            $two_variant_product = Product::query()
                ->join(
                    'variants',
                    'variants.product_id',
                    '=',
                    'products.id',
                )
                ->join(
                    'variant_values',
                    'variant_values.variant_id',
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
                    'variant_combination.id',
                )
                ->select([
                    'products.id',
                    'products.name',
                    'products.price',
                ])
                ->with('variants.variantValues.combinations')
                ->where('second_variant_combination.id', $product_id)
                ->orWhere('variant_combination.id', $product_id)
                ->orWhere('variant_values.id', $product_id)
                ->first();

            Debugbar::info('product with two variant');

            return GetProductDetailsData::from($two_variant_product, $product_id);
        }

        $product_has_three_variants =
            $product_variants_count == 3;

        if ($product_has_three_variants) {

            $product = Product::query()
                ->join(
                    'variants',
                    'variants.product_id',
                    '=',
                    'products.id',
                )
                ->join(
                    'variant_values',
                    'variant_values.variant_id',
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
                    'variant_combination.id',
                )
                ->select([
                    'products.id',
                    'products.name',
                    'products.price',
                ])
                ->with(['variants' => [
                    'variantValues' => [
                        'combinations' => [
                            'combinations',
                        ],
                        'combined_by' => [
                            'combinations',
                        ],
                    ],
                ]])
                ->where('second_variant_combination.id', $product_id)
                ->orWhere('variant_combination.id', $product_id)
                ->orWhere('variant_values.id', $product_id)
                ->first();

            Debugbar::info('product with three variant');

            return $product;

            return GetProductDetailsData::from($product, $product_id);
        }

        $three_variant_product = Product::query()
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

        return GetProductDetailsData::from($three_variant_product, $product_id);

    }
}
