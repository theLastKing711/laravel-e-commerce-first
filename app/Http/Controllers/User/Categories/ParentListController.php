<?php

namespace App\Http\Controllers\User\Categories;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\User\Category\Index\CursorPaginatedListData;
use App\Data\User\Category\Index\ParentListData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use OpenApi\Attributes as OAT;

class ParentListController extends Controller
{
    #[OAT\Get(path: '/user/categories/parent-list', tags: ['userCategories'])]
    #[QueryParameter('cursor')]
    #[SuccessListResponse(CursorPaginatedListData::class)]
    public function __invoke()
    {

        $categories = Category::query()
            ->with(['medially' => function ($query) {
                $query->select('file_url', 'medially_id')
                    ->take(1);
            }])
            ->cursorPaginate(10);

        return ParentListData::collect(
            $categories
        );
    }
}
