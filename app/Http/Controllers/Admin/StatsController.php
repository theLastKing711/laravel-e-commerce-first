<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Category\PaginatedCategoryData;
use App\Data\Admin\Stats\BestSellingProductsQueryParameterData;
use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

class StatsController extends Controller
{
    #[OAT\Get(path: '/admin/stats', tags: ['stats'])]
    #[SuccessItemResponse(PaginatedCategoryData::class)]
    #[QueryParameter('start_at')]
    #[QueryParameter('end_at')]
    public function getBestSellingProducts(
        BestSellingProductsQueryParameterData $query_params
    ) {

        Log::info('accessing Admin StatsController');

        $start_date = '2024-09-10 13:58:37';
        $end_date = '2024-09-15 13:58:37';

        //        $product_stats = Product::query()
        //            ->select(['id', 'name'])
        ////            ->addSelect(
        ////                [
        ////                    'total_sales' => Product::query()
        ////                        ->whereColumn('id', 'products.id')
        ////                        ->selectRaw('SUM(COALESCE(price, price_offer))'),
        ////                ]
        ////            )
        //            ->addSelect(
        //                [
        //                    'total_sales' => orderDetails::query()
        //                        ->whereColumn('product_id', 'products.id')
        //                        ->selectRaw('SUM(COALESCE(products.price, products.price_offer) * quantity)'),
        //                ]
        //            )
        //            ->withSum(
        //                [
        //                    'orderDetails' => fn ($query) => $query->whereBetween('created_at', [$query_params->start_at, $query_params->end_at]),
        //                ],
        //                'quantity'
        //            )
        //            ->orderByDesc('order_details_sum_quantity')
        //            ->get();

        //        return Category::with('products.orderDetails')
        //                        ->get();

        //        $child_category_stats = Category::query()
        //            ->isChild()
        //            ->select(['id', 'name'])
        //            ->addSelect(
        //                [
        //                    'total_products' => Product::query()
        //                        ->whereColumn('category_id', 'categories.id')
        ////                        ->with(['orderDetails'])
        //                        ->join('order_details', 'order_details.product_id', 'products.id')
        //                        ->selectRaw('SUM(order_details.quantity)'),
        //                ]
        //            )
        //            ->withSum(
        //                'products',
        //                'unit'
        //            )
        //            ->get();

        $parent_category_stats = Category::query()
            ->isParent()
            ->select(['id', 'name'])
            ->selectRaw('                            (SELECT COALESCE(
                                SUM(
                                    COALESCE(rn.price, rn.price_offer) * order_details.quantity
                                ),
                                0
                            )
                            from
                            (
                                SELECT products.id as product_id,
                                products.price,
                                products.price_offer,
                                ROW_NUMBER() OVER(PARTITION BY products.id ORDER BY products.id) as num
                                FROM categories as c2
                                INNER JOIN category_product ON c2.id = category_product.category_id
                                INNER JOIN products ON products.id = category_product.product_id
                                LEFT JOIN categories as child_categories
                                ON c2.id = child_categories.parent_id
                                where c2.parent_id = categories.id
                            ) as rn
                            INNER JOIN order_details on rn.product_id = order_details.product_id
                            WHERE num = 1
                            ) as total_products_revenue_for_category')
            ->selectRaw('(SELECT COALESCE(SUM(order_details.quantity),0)
                            from
                            (
                                SELECT products.id as product_id,c2.id as category_id,
                                c2.name as category_name,
                                ROW_NUMBER() OVER(PARTITION BY products.id ORDER BY products.id) as num
                                FROM categories as c2
                                INNER JOIN category_product ON c2.id = category_product.category_id
                                INNER JOIN products ON products.id = category_product.product_id
                                LEFT JOIN categories as child_categories
                                ON c2.id = child_categories.parent_id
                                where c2.parent_id = categories.id
                            ) as rn
                            INNER JOIN order_details on rn.product_id = order_details.product_id
                            WHERE num = 1
                            ) as total_products_sold_for_category'
            )
            ->get();

        return $parent_category_stats;
        //        OrderDetails::query()
        //            ->with([
        //                'products' => [
        //                    'categories',
        //                ],
        //            ])
        //            ->whereBetween('created_at', [$start_date, $end_date])
        //            ->groupBy('product_id')
        //            ->sum()

        //        return $product_stats;

    }
}
