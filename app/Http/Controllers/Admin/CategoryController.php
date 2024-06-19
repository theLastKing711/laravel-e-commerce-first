<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Category\CategoryData;
use App\Data\Admin\Category\CreateCategoryData;
use App\Data\Admin\Category\PathParameters\CategoryIdPathParameterData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\FileService;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;
use Storage;


#[
    OAT\Info(version: '1', title: 'Categories Controller'),
    OAT\OpenApi(x: ['tagGroups' => ['name' => 'testing',  'tags' => 'categories']]),
    OAT\PathItem(
        path: "/admin/categories/{id}",
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminCategoryIdPathParameter',
            )
        ]
    )
]
class CategoryController extends Controller
{
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
                    ref: '#/components/schemas/adminCategory'
                )),
            )
        ],
    )]
    public function index(FileService $fileService)
    {
        $categoriesData = Category::all()->map(
            function (Category $category) use ($fileService) {
                return new CategoryData(
                    id: $category->id,
                    name: $category->name,
                    image: $fileService->getWebLocation('category', $category->image),
                    created_at: $category->created_at,
                );
        });

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
                mediaType: "multipart/form-data",
                schema: new OAT\Schema(
                    ref: '#/components/schemas/adminCreateCategory',
                ),
            ),
        ),
        tags: ['categories'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Category was successfully created',
                content: new OAT\JsonContent(ref: '#/components/schemas/adminCategory'),
            )
        ],
    )]
    public function store(
        CreateCategoryData $createCategoryData,
        FileService $fileService
    )
    {
        Log::info('Processing Admin Store Category Controller');
        Log::info('Request category {category}', ['category' => $createCategoryData]);

        $categoryImage = $createCategoryData->image;
        Log::info('category image {image}', ['image' => $categoryImage], );

        $uploadedFileUrl = $fileService
                                ->upload('category', $categoryImage);

        $category = Category::create([
            'name' => $createCategoryData->name,
            'image' => $uploadedFileUrl,
        ]);

        Log::info('created {category}', ['category' => $category]);

        Log::info('hello world {world}', ['world' => $createCategoryData]);

        return CategoryData::from($category);
    }

    #[OAT\Get(
        path: '/admin/categories/{id}',
        tags: ['categories'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'The Category was successfully updated',
                content: new OAT\JsonContent(
                    ref: '#/components/schemas/adminCategory'
                ),
            )
        ],
    )]
    public function show(CategoryIdPathParameterData $request, FileService $fileService)
    {
        Log::info('category id {id}', ['id' => $request->id]);

        $category = Category::find($request->id);

        Log::info('category {category}', ['category' => $category]);

        $categoryImageWebLocation = $fileService
                                        ->getWebLocation('category', $category->image);

        Log::info(
            'image web location {image}',
            ['image' => $categoryImageWebLocation]
        );

        return CategoryData::from([
            'id' => $category->id,
            'name' => $category->name,
            'image' => $categoryImageWebLocation,
            'created_at' => $category->created_at,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        //
    }
}
