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
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\MediaService;
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

        $search_filter = $query_parameters->search;

        $parent_ids_filter = $query_parameters->ids;

        Log::info($query_parameters);

        $per_page = $query_parameters->perPage ?? 10;

        $sort = $query_parameters->sort;

        $created_at_date_range_filter_array = $query_parameters->date_range;
        Log::info($per_page);

        $categories = Category::query()
            ->leftJoin(
                'categories as parent',
                'categories.parent_id',
                '=',
                'parent.id',
            )// can now reference related table(same table with sub-query) using parent.$column
            // in subsequent elequent methods
            ->when($search_filter, function (Builder $query) use ($search_filter) {
                return $query->whereAnyLike(
                    [
                        'categories.id',
                        'categories.name',
                        'categories.created_at',
                    ],
                    $search_filter
                );
            })
            ->when($parent_ids_filter, function (Builder $query) use ($parent_ids_filter) {

                return $query->whereIn(
                    'categories.parent_id',
                    $parent_ids_filter
                );
            })
            ->when($sort, function (Builder $query) use ($sort) {

                [$sort_field, $sort_value] = explode(' ', $sort);

                return $query->orderByDynamic($sort_field, $sort_value);
            })
            ->when($created_at_date_range_filter_array, function (Builder $query) use ($created_at_date_range_filter_array) {

                [$startDate, $endDate] = $created_at_date_range_filter_array;

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
                'categories.image',
                'categories.created_at',
                'parent.name as parent_name',
            ])
            ->paginate(perPage: $per_page);

        //        Log::info($categories);

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

        Log::info('Accessing CategoryController store method');

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
        UpdateCategoryData $updatedCategoryData,
    ) {

        Log::info(
            'accessing Admin CategoryController update method with id {id}',
            ['id' => $request->id]
        );

        Log::info($updatedCategoryData);

        $category = Category::find($request->id);

        $isCategoryUpdated = $category->update([
            'name' => $updatedCategoryData->name,
            'parent_id' => $updatedCategoryData->parent_id,
        ]);

        if ($isCategoryUpdated) {

            $request_image_list = $updatedCategoryData->image_urls;

            MediaService::updateMediaForModel($category, $request_image_list);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(path: '/admin/categories/{id}', tags: ['categories'])]
    #[SuccessNoContentResponse('Item Deleted Successfully')]
    public function destroy(CategoryIdPathParameterData $path_parameters): bool
    {
        $categoryToDelete = Category::find($path_parameters->id);

        MediaService::removeAssocciatedMediaForModel($categoryToDelete);

        $isCategoryDeleted = $categoryToDelete->delete();

        return $isCategoryDeleted;

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

        $categoryChilds =
            Category::whereParentId($request->id)
                ->select(['id', 'parent_id', 'name', 'image', 'created_at'])
                ->orderByDesc('created_at')
                ->get();

        return CategoryData::collect($categoryChilds);
    }

    /** Get child Categories by parent Ids List */
    #[OAT\Get(path: '/admin/categories/GetSubCategoriesByParents', tags: ['categories'])]
    #[QueryParameter('name')]
    #[ListQueryParameter]
    #[SuccessListResponse(CategoryData::class)]
    public function GetSubCategoriesByParents(CategoryIdsQueryParameterData $query_parameters)
    {

        Log::info('accessing Admin CategoryController GetSubCategoriesByParents method');

        $parentIds = $query_parameters->ids;

        Log::info(
            'accessing CategoryController, GetSubCategoriesByParents Method with ids  {ids}',
            ['ids' => $query_parameters->ids]
        );

        $childCategories =
            Category::query()
                ->isChild()
                ->hasParents($parentIds)
                ->get();

        return CategoryData::collect($childCategories);
    }
}
