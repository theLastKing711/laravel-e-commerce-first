<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Category\CategoryData;
use App\Data\Admin\Category\CategoryShowData;
use App\Data\Admin\Category\CreateCategoryData;
use App\Data\Admin\Category\PaginatedCategoryData;
use App\Data\Admin\Category\PathParameters\CategoryIdPathParameterData;
use App\Data\Admin\Category\QueryParameters\CategoryIdsQueryParameterData;
use App\Data\Admin\Category\QueryParameters\Index\CategoryIndexQueryParameterData;
use App\Data\Admin\Category\UpdateCategoryData;
use App\Data\Shared\ListData;
use App\Data\Shared\Swagger\Parameter\QueryParameter\ListQueryParameter;
use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Request\JsonRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Facades\MediaService;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[
    OAT\Info(version: '1', title: 'Categories Controller'),
    // set global security header parameter in swagger ui,
    // we must choose it (ref_to_be_used_below_csrf) in bellow attribute
    OAT\SecurityScheme(
        securityScheme: 'ref_to_be_used_below_csrf',
        type: 'apiKey',
        name: 'X-XSRF-TOKEN',
        in: 'header',
    ),
    OAT\OpenApi(security: [
        ['ref_to_be_used_below_csrf' => []],
    ]),
    OAT\PathItem(
        path: '/admin/categories/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminCategoryIdPathParameter',
            ),
        ],
    ),
    OAT\PathItem(
        path: '/admin/categories/getSubCategories/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminCategoryIdPathParameter',
            ),
        ],
    )
]
class CategoryController extends Controller
{
    private string $mainRoute = '/admin/categories';

    /**
     * Get All Categories
     */
    #[OAT\Get(path: '/admin/categories', tags: ['categories'])]
    #[QueryParameter('page', 'integer')]
    #[QueryParameter('perPage', 'integer')]
    #[QueryParameter('search')]
    #[ListQueryParameter]
    #[ListQueryParameter('date_range')]
    #[QueryParameter('sort')]
    #[SuccessItemResponse(PaginatedCategoryData::class)]
    public function index(
        CategoryIndexQueryParameterData $query_parameters
    ) {

        Log::info('accessing Admin CategoryController index method');

        $request_search_filter = $query_parameters->search;

        $request_parent_ids_filter = $query_parameters->ids;

        Log::info($query_parameters);

        $request_per_page = $query_parameters->perPage ?? 10;

        $request_sort = $query_parameters->sort;

        $request_created_at_filter = $query_parameters->date_range;
        Log::info($request_per_page);

        $categories = Category::query()
            ->leftJoin(
                'categories as parent',
                'categories.parent_id',
                '=',
                'parent.id',
            )// can now reference related table(same table with sub-query) using parent.$column
            // in subsequent eloquent methods
            ->when($request_search_filter, function (Builder $query) use ($request_search_filter) {
                return $query->whereAnyLike(
                    [
                        'categories.id',
                        'categories.name',
                        'categories.created_at',
                    ],
                    $request_search_filter
                );
            })
            ->when($request_parent_ids_filter, function (Builder $query) use ($request_parent_ids_filter) {

                return $query->whereIn(
                    'categories.parent_id',
                    $request_parent_ids_filter
                );
            })
            ->when($request_sort, function (Builder $query) use ($request_sort) {

                [$sort_field, $sort_value] = explode(' ', $request_sort);

                return $query->orderByDynamic($sort_field, $sort_value);
            })
            ->when($request_created_at_filter, function (Builder $query) use ($request_created_at_filter) {

                [$startDate, $endDate] = $request_created_at_filter;

                return $query->whereBetween(
                    'categories.created_at',
                    [
                        date('Y/m/d', $startDate / 1000),
                        date('Y/m/d', $endDate / 1000),
                    ]
                );
            })
            ->select([
                'categories.id',
                'categories.parent_id',
                'categories.name',
                'categories.created_at',
                'parent.name as parent_name',
            ])
            ->paginate(perPage: $request_per_page);

        $categoriesData = CategoryData::collect($categories);

        return $categoriesData;
    }

    /**
     * Create a new Category.
     */
    #[OAT\Post(path: '/admin/categories', tags: ['categories'])]
    #[JsonRequestBody(CreateCategoryData::class)]
    #[SuccessNoContentResponse('Category created successfully')]
    public function store(
        CreateCategoryData $createCategoryData,
    ) {

        Log::info($createCategoryData);

        $category_cloudinary_public_ids = $createCategoryData
            ->image_urls
            ->pluck(['public_id']);

        Log::info($category_cloudinary_public_ids);
        $category = Category::create([
            'name' => $createCategoryData->name,
            'parent_id' => $createCategoryData->parent_id,
        ]);

        MediaService::createMediaForModel($category, $category_cloudinary_public_ids);

    }

    /** Get sub Categories by the id of the category */
    #[OAT\Get(path: '/admin/categories/list', tags: ['categories'])]
    #[SuccessNoContentResponse('Category list fetched successfully')]
    public function list()
    {
        Log::info(
            'accessing Admin CategoryController, list method',
        );

        $categories =
            Category::query()
                ->select(['id', 'name'])
                ->get();

        return ListData::collect($categories);
    }

    #[OAT\Get(path: '/admin/categories/{id}', tags: ['categories'])]
    #[SuccessItemResponse(CategoryData::class, 'Fetched category successfully')]
    public function show(CategoryIdPathParameterData $request)
    {

        Log::info(
            'accessing Admin CategoryController show method with path id {id}',
            ['id' => $request->id]
        );

        $category = Category::query()
            ->find($request->id);

        return CategoryShowData::from($category);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OAT\Patch(path: '/admin/categories/{id}', tags: ['categories'])]
    #[JsonRequestBody(UpdateCategoryData::class)]
    #[SuccessNoContentResponse('Category was updated successfully')]
    public function update(
        CategoryIdPathParameterData $request,
        UpdateCategoryData $updated_category_data,
    ) {

        Log::info(
            'accessing Admin CategoryController update method with id {id}',
            ['id' => $request->id]
        );

        $category = Category::find($request->id);

        $is_category_updated = $category->update([
            'name' => $updated_category_data->name,
            'parent_id' => $updated_category_data->parent_id,
        ]);

        if ($is_category_updated) {

            $request_image_list = $updated_category_data->image_urls;

            MediaService::updateMediaForModel($category, $request_image_list);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(path: '/admin/categories/{id}', tags: ['categories'])]
    #[SuccessNoContentResponse('Item Deleted Successfully')]
    public function destroy(CategoryIdPathParameterData $path_parameters)
    {

        Log::info(
            'accessing Admin CategoryController destroy method with id {id}',
            ['id' => $path_parameters->id]
        );

        $category_to_delete = Category::find($path_parameters->id);

        MediaService::removeAssociatedMediaForModel($category_to_delete);

        $isCategoryDeleted = $category_to_delete->delete();
        if ($isCategoryDeleted) {
            Log::info(Category::whereId('id', $path_parameters->id)->get());
            Log::info('category deleted');
        }

    }

    /** Get Parent Categories List*/
    #[OAT\Get(path: '/admin/categories/parentList', tags: ['categories'])]
    #[SuccessListResponse(ListData::class)]
    public function getParentCategoriesList()
    {
        Log::info(
            'accessing Category Controller, getParentCategories method',
        );

        $parent_categories = Category::query()
            ->isParent()
            ->get();

        return ListData::collect($parent_categories);
    }

    /** Get Child Categories List */
    #[OAT\Get(path: '/admin/categories/getSubCategories/{id}', tags: ['categories'])]
    #[SuccessItemResponse(CategoryData::class)]
    public function getSubCategories(CategoryIdPathParameterData $request)
    {
        Log::info(
            'accessing Category Controller, getSubCategories method with path id {id}',
            ['id' => $request->id]
        );

        $category_childs =
            Category::whereParentId($request->id)
                ->select(['id', 'parent_id', 'name', 'image', 'created_at'])
                ->orderByDesc('created_at')
                ->get();

        return CategoryData::collect($category_childs);
    }

    /** Get child Categories by parent Ids List */
    #[OAT\Get(path: '/admin/categories/GetSubCategoriesByParents', tags: ['categories'])]
    #[QueryParameter('name')]
    #[ListQueryParameter]
    #[SuccessListResponse(CategoryData::class)]
    public function GetSubCategoriesByParents(CategoryIdsQueryParameterData $query_parameters)
    {

        Log::info('accessing Admin CategoryController GetSubCategoriesByParents method');

        $request_parent_ids = $query_parameters->ids;

        Log::info(
            'accessing CategoryController, GetSubCategoriesByParents Method with ids  {ids}',
            ['ids' => $query_parameters->ids]
        );

        $child_categories =
            Category::query()
                ->isChild()
                ->hasParents($request_parent_ids)
                ->get();

        return CategoryData::collect($child_categories);
    }
}
