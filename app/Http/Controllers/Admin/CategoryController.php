<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Category\CategoryData;
use App\Data\Admin\Category\CreateCategoryData;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OAT;

#[OAT\Info(version: '1', title: 'Categories Controller')]
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
                content: new OAT\JsonContent(ref: '#/components/schemas/adminCategory'),
            )
        ],
    )]
    public function index()
    {
        Log::info('hello world');
        return CategoryData::collect(Category::all());
    }

    /**
     * Create a new Category.
     */
    #[OAT\Post(
        path: '/admin/categories',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\JsonContent(ref: '#/components/schemas/adminCreateCategory'),
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
    public function store(CreateCategoryData $createCategoryData)
    {
        $category = Category::create($createCategoryData->all());

        Log::info('created {category}', ['category' => $category]);

        return CategoryData::from($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
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
