<?php

namespace App\Http\Controllers\User\Product;

use App\Data\Admin\User\Product\ProductIdData;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/user/products/favourite/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/userProductIdPathParameter',
            ),
        ],
    )
]
class FavouriteProductController extends Controller
{
    /**
     * Handle the incoming request.
     */
    #[OAT\Post(path: '/user/products/favourite/{id}', tags: ['userProducts'])]
    #[SuccessNoContentResponse]
    public function __invoke(ProductIdData $request)
    {

        Log::info(
            'accessing User FavouriteProductController with id {id}',
            ['id' => $request->id]
        );

        $logged_user = User::query()
            ->whereId(Auth::user()->id)
            ->first();

        $logged_user->favouriteProducts()
            ->toggle($request->id);

    }
}
