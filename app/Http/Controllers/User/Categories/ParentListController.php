<?php

namespace App\Http\Controllers\User\Categories;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\User\Category\Index\CursorPaginatedListData;
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
        return CursorPaginatedListData::from(
            Category::cursorPaginate(15)
        );
    }
}
