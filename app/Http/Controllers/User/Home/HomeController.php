<?php

namespace App\Http\Controllers\User\Home;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\User\Home\AllCategoriesData;
use App\Data\User\Home\HomeData;
use App\Data\User\Home\HomeProductListData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use OpenApi\Attributes as OAT;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    #[OAT\Get(path: '/user/home', tags: ['userHome'])]
    #[QueryParameter('name')]
    #[SuccessItemResponse(HomeData::class)]
    public function __invoke()
    {

        $logged_user_id = 21;

        $all_categories = Category::all();

        $most_selling_products = $logged_user_id === 21 ?
        DB::select(
            '
        select id, name, price,
        case
            when (
                select count(*)
                from user_favourite_product
                where user_favourite_product.product_id = products.id
                and user_favourite_product.user_id = ?
            ) > 0
            then 1
            else 0
        end as is_favourite
        from products
        LIMIT 10
        ',
            [21]
        )
        :
        [];

        $user_purchased_products = $logged_user_id === 21 ?
        DB::select(
            '
        select id, name, price,
        case
            when (
                select count(*)
                from user_favourite_product
                where user_favourite_product.product_id = rn.id
                and user_favourite_product.user_id = ?
            ) > 0
            then 1
            else 0
        end as is_favourite
        from (
            select
            products.id as id,
            products.name as name,
            products.price as price,
            ROW_NUMBER() OVER(PARTITION BY products.id) as num
            from users
            inner join orders
            on
            users.id = orders.user_id
            inner join order_details
            on order_details.order_id = orders.id
            inner join products
            on order_details.product_id = products.id
            where users.id = ?
            order by orders.created_at desc
        ) as rn
        where rn.num = 1
        LIMIT 10
        ',
            [21, 21]
        )
        :
        [];

        return HomeData::from([
            'categories' => AllCategoriesData::collect($all_categories),
            'most_selling_products' => HomeProductListData::collect($most_selling_products),
            'user_purchased_products' => HomeProductListData::collect($user_purchased_products),
        ]);

    }
}
