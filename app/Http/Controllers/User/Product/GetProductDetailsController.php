<?php

namespace App\Http\Controllers\User\Product;

use App\Data\Admin\User\Product\Details\GetProductDetailsData;
use App\Data\Admin\User\Product\Details\GetProductDetailsRequestData;
use App\Data\Admin\User\Product\Details\QueryParameters\GetProductDetailsQueryParameterData;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\Debugbar\Facades\Debugbar;
use Log;
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

        // /** @var Product $product */
        // $product =
        //     Product::query()
        //         ->has('variants', 3)
        //         ->with(
        //             ['variants' => fn ($query) => $query
        //                 ->with('variantValues', fn ($query) => $query->chaperone())
        //                 ->chaperone(),
        //             ]
        //         )
        //         ->first();

        // $product
        //     ->variants
        //     ->each(function ($variant) {

        //         Log::info($variant->product);
        //         Log::info($variant->variantValues->first()->variant);

        //     });

        // return $product->variants->first()->product;

        // Debugbar::info($request);

        // return Product::query()->with([
        //     'variants' => [
        //         'variantValues' => [
        //             'combinations' => [
        //                 'pivot' => [
        //                     'first_variant_value',
        //                 ],
        //             ],
        //         ],
        //     ],
        // ])->first();

        $request_variant_value_ids_query_parameters =
            new GetProductDetailsQueryParameterData(
                first_variant_value_id: $request->first_variant_value_id,
                second_variant_value_id: $request->second_variant_value_id,
                third_variant_value_id: $request->third_variant_value_id
            );

        Debugbar::info($request);

        $product_id = $request->id;

        $product_variants_count = Product::query()
            ->withCount('variants')
            ->firstWhere('id', $request->id)
            ->variants_count;

        $product_variants_count = $product_variants_count;

        $product_has_one_variant = $product_variants_count == 1;

        if ($product_has_one_variant) {

            /** @var Product $one_variant_product */
            $one_variant_product = Product::query()
                // ->join(
                //     'variants',
                //     'products.id',
                //     '=',
                //     'variants.product_id',
                // )
                // ->join(
                //     'variant_values',
                //     'variants.id',
                //     '=',
                //     'variant_values.variant_id',
                // )
                // ->select([
                //     'products.id',
                //     'products.name',
                //     'products.price',
                // ])
                ->with([
                    'medially',
                    'variants' => [
                        'variantValues' => [
                            'medially',
                        ],
                    ],
                ])
                ->firstWhere('products.id', $request->id);

            Debugbar::info('product with one variant');

            return GetProductDetailsData::fromMultiple(
                $one_variant_product,
                $request_variant_value_ids_query_parameters
            );
        }

        $product_has_two_variants =
            $product_variants_count == 2;

        if ($product_has_two_variants) {

            /** @var Product $two_variant_product */
            $two_variant_product = Product::query()
                // ->join(
                //     'variants',
                //     'products.id',
                //     '=',
                //     'variants.product_id',
                // )
                // ->join(
                //     'variant_values',
                //     'variants.id',
                //     '=',
                //     'variant_values.variant_id',
                // )
                // ->join(
                //     'variant_combination',
                //     'variant_values.id',
                //     '=',
                //     'variant_combination.first_variant_value_id',
                // )
                // ->join(
                //     'variant_combination as combined_variant_combination',
                //     'variant_values.id',
                //     '=',
                //     'combined_variant_combination.second_variant_value_id',
                // )
                // ->select([
                //     'products.id',
                //     'products.name',
                //     'products.price',
                //     'variants.*',
                //     'variant_values.*',
                // ])
                ->with([
                    'medially',
                    'variants' => [
                        'variantValues' => [
                            'medially',
                            'combinations' => [
                                'medially',
                                'pivot' => [
                                    'medially',
                                ],
                            ],
                            'combined_by' => [
                                'medially',
                                'pivot' => [
                                    'medially',
                                ],
                            ],
                        ],
                    ],
                ])
                ->firstWhere('products.id', $request->id);

            // Debugbar::info($two_variant_product->variants);

            Debugbar::info('product with two variant');

            DebugBar::info($two_variant_product->variants);

            return GetProductDetailsData::fromMultiple(
                $two_variant_product,
                $request_variant_value_ids_query_parameters
            );
        }

        $product_has_three_variants =
            $product_variants_count == 3;

        if ($product_has_three_variants) {

            DebugBar::info('product with three variants');

            /** @var Product $product */
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
                ->with(
                    [
                        'variants' => [
                            'variantValues' => [
                                'combinations' => [
                                    'pivot' => [
                                        'combinations' => [
                                            'pivot' => [
                                                'variantCombination',
                                            ],
                                        ],
                                    ],
                                ],
                                'combined_by' => [
                                    'pivot' => [
                                        'combinations' => [
                                            'pivot' => [
                                                'medially',
                                                'variantCombination',
                                            ],
                                        ],
                                    ],
                                ],
                                'late_combinations',
                            ],
                        ],
                    ]
                )
                ->firstWhere('products.id', $request->id);

            return GetProductDetailsData::fromMultiple(
                $product,
                $request_variant_value_ids_query_parameters
            );
        }

        // $three_variant_product = Product::query()
        //     ->select('id', 'name', 'price')
        //     ->whereId($product_id)
        //     ->selectRaw(
        //         '
        //     case
        //     when (
        //     select count(*)
        //     from user_favourite_product
        //     where exists (
        //             select true
        //             where user_favourite_product.product_id = products.id
        //             and user_favourite_product.user_id = ?
        //         )
        //     ) >= 1
        //     then 1
        //     else 0
        //     end as is_favourite',
        //         [21]
        //     )
        //     ->first();

        $no_variation_product =
            Product::query()
                ->with([
                    'medially',
                    'variants' => [
                        'variantValues' => [
                            'medially',
                        ],
                    ],
                ])
                ->firstWhereId($request->id);

        return GetProductDetailsData::fromMultiple(
            $no_variation_product,
            $request_variant_value_ids_query_parameters
        );

    }
}
