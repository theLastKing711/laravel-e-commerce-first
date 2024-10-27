<?php

namespace App\Http\Controllers\User\Product;

use App\Data\Admin\User\Product\CursorPaginatedFavouriteProductData;
use App\Data\Admin\User\Product\GetUserFavouriteProductsData;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Log;
use OpenApi\Attributes as OAT;

class GetUserFavouriteProductsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    #[OAT\Get(path: '/user/products/favourite', tags: ['userProducts'])]
    #[SuccessItemResponse(CursorPaginatedFavouriteProductData::class)]
    public function __invoke()
    {

        Log::info(
            'accessing User GetUserFavouriteProductsController',
        );

        // $logged_user_id = Auth::User()->id;

        $logged_user_id = 21;

        $cursor_paginated_products = Product::query()
            ->select('id', 'name', 'price')
            ->with('medially', function ($query) {
                $query->first();
            })
            ->whereHas('favouritedByUsers', function ($query) {
                return $query->where('user_id', 21);
            })
            ->cursorPaginate(15);

        return $cursor_paginated_products;

        return GetUserFavouriteProductsData::collect($cursor_paginated_products);

    }
}
