<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Category\CategoryData;
use App\Data\Admin\Category\CreateCategoryData;
use App\Data\Admin\Category\ParentCategoryIdsData;
use App\Data\Admin\Category\PathParameters\CategoryIdPathParameterData;
use App\Data\Admin\Category\UpdateCategoryData;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\FileService;
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
    #[OAT\Get(
        path: '/admin/categories',
        tags: ['categories'],
        //        parameters: [
        //            new OAT\QueryParameter(
        //                required: false,
        //                ref: "#/components/parameters/categorySearch",
        //            ),
        //            new OAT\QueryParameter(
        //                required: false,
        //                ref: "#/components/parameters/categoryOrderBy",
        //            ),
        //            new OAT\QueryParameter(
        //                required: false,
        //                ref: "#/components/parameters/categoryDirection"
        //            ),
        //            new OAT\QueryParameter(
        //                required: false,
        //                ref: "#/components/parameters/categoryPerPage"
        //            ),
        //            new OAT\QueryParameter(
        //                required: false,
        //                ref: "#/components/parameters/categoryPage"
        //            ),
        //        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Category was successfully created',
                //                content: new OAT\JsonContent(ref: '#/components/schemas/paginatedCategory'),
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: CategoryData::class
                    ),
                ),
            ),
        ],
    )]
    public function index()
    {

        //        Log::info('accessing CategoryController index method');
        //
        //                Log::info('categories parent_ids {categories} ', ['categories' => Category::pluck('parent_id')]);

        $categoriesData = CategoryData::collect(
            Category::all()->select([
                'id',
                'parent_id',
                'name',
                'image',
                'created_at',
            ])
        );

        Log::info(
            'Fetched categories {categories}',
            ['categories' => $categoriesData]
        );

        return $categoriesData;
    }

    /**
     * Create a new Category.
     */
    #[OAT\Post(
        path: '/admin/categories',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OAT\Schema(
                    type: CreateCategoryData::class,
                ),
            ),
        ),
        tags: ['categories'],
        parameters: [

        ],
        responses: [
            // new OAT\Response(
            //     response: 200,
            //     description: 'Category Created Successfully',
            //     content: new OAT\JsonContent(type: CategoryData::class),
            // ),
            new OAT\Response(
                response: 200,
                description: 'Category was created successfully',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: CategoryData::class,
                    ),
                ),
            ),
        ],
    )]
    public function store(
        CreateCategoryData $createCategoryData,
    ): CategoryData {

        Log::info('Accessing CategoryController store method');

        $categoryImage = $createCategoryData->image;

        $uploadedFileUrl = FileService::upload('category', $categoryImage);

        $category = Category::create([
            'name' => $createCategoryData->name,
            'parent_id' => $createCategoryData->parent_id,
            'image' => $uploadedFileUrl,
        ]);

        Log::info('Category was created {category}', ['category' => $category]);

        return CategoryData::from($category);
    }

    #[OAT\Get(
        path: '/admin/categories/{id}',
        tags: ['categories'],
        parameters: [

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'category fetched successfully',
                content: new OAT\JsonContent(type: CategoryData::class),
            ),
        ],
    )]
    public function show(CategoryIdPathParameterData $request)
    {

        Log::info(
            'accessing Category Controller show method with path id {id}',
            ['id' => $request->id]
        );

        $category = Category::find($request->id);

        Log::info(
            'Fetched category {category}',
            ['category' => $category]
        );

        return CategoryData::from($category);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OAT\Patch(
        path: '/admin/categories/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OAT\Schema(
                    type: UpdateCategoryData::class,
                ),
            ),
        ),
        tags: ['categories'],
        parameters: [

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Category was updated successfully',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: CategoryData::class,
                    ),
                ),
            ),
        ],
    )]
    public function update(
        CategoryIdPathParameterData $request,
        UpdateCategoryData $updatedCategoryData,
    ) {
        $category = Category::find($request->id);

        $isCategoryUpdated = $category->update([
            'name' => $request->name,
            'image' => $request->image,
        ]);

        if ($isCategoryUpdated) {
            FileService::delete('category', $category->image);
        }

        return CategoryData::from($category);

    }

    /**
     * Remove the specified resource from storage.
     */
    #[OAT\Delete(
        path: '/admin/categories/{id}',
        tags: ['categories'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'The Category was successfully deleted',
            ),
        ],
    )]
    public function destroy(CategoryIdPathParameterData $request): bool
    {
        $categoryToDelete = Category::find($request->id);

        $isCategoryDeleted = $categoryToDelete->delete();

        return $isCategoryDeleted;

    }

    /** Get sub Categories by the id of the category */
    #[OAT\Get(
        path: '/admin/categories/getSubCategories/{id}',
        tags: ['categories'],
        parameters: [

        ],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'category fetched successfully',
                content: new OAT\JsonContent(type: CategoryData::class),
            ),
        ],
    )]
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
    #[OAT\Post(
        path: '/admin/categories/GetSubCategoriesByParents',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(type: ParentCategoryIdsData::class),
        ),
        tags: ['categories'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Fetched Categories successfully',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: CategoryData::class,
                    ),
                ),
            ),
        ],
    )]
    public function GetSubCategoriesByParents(ParentCategoryIdsData $request)
    {

        $parentIds = $request->ids;

        Log::info(
            'accessing CategoryController, GetSubCategoriesByParents Method with ids  {ids}',
            ['ids' => $request->ids]
        );

        $childCategories =
            Category::isChild()
                ->hasParents($parentIds)
                ->get();

        return CategoryData::collect($childCategories);
    }
}
