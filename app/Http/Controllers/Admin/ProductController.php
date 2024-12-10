<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Admin\PathParameters\AdminProductIdPathParameterData;
use App\Data\Admin\Product\CreateProductData;
use App\Data\Admin\Product\PaginatedProductData;
use App\Data\Admin\Product\ProductData;
use App\Data\Admin\Product\ProductListData;
use App\Data\Admin\Product\QueryParameters\ProductNameQueryParameterData;
use App\Data\Admin\Product\UpdateProductData;
use App\Data\Shared\Pagination\QueryParameters\PaginationQueryParameterData;
use App\Data\Shared\Swagger\Property\QueryParameter;
use App\Data\Shared\Swagger\Request\FormDataRequestBody;
use App\Data\Shared\Swagger\Response\SuccessItemResponse;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\Shared\Swagger\Response\SuccessNoContentResponse;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\FileService;
use Log;
use OpenApi\Attributes as OAT;

#[
    OAT\PathItem(
        path: '/admin/products/{id}',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminProductIdPathParameter',
            ),
        ],
    ),
    OAT\PathItem(
        path: '/admin/products/{id}/activate',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminProductIdPathParameter',
            ),
        ],
    ),
    OAT\PathItem(
        path: '/admin/products/{id}/deActivate',
        parameters: [
            new OAT\PathParameter(
                ref: '#/components/parameters/adminProductIdPathParameter',
            ),
        ],
    ),
]
class ProductController extends Controller
{
    #[OAT\Get(path: '/admin/products', tags: ['products'])]
    #[QueryParameter('page', 'integer')]
    #[QueryParameter('perPage', 'integer')]
    #[SuccessItemResponse(PaginatedProductData::class)]
    public function index(
        PaginationQueryParameterData $query_parameters,
    ) {

        Log::info('accessing ProductController index method');

        Log::info('value {value}', ['value' => $query_parameters]);

        return ProductData::collect(
            Product::with('categories.parent')
                ->paginate(perPage: $query_parameters->perPage)
        );

    }

    #[OAT\Get(path: '/admin/products/{id}', tags: ['products'])]
    #[SuccessItemResponse(ProductData::class)]
    public function show(AdminProductIdPathParameterData $request)
    {

        $product =
            Product::with(['categories.parent'])
                ->where('id', $request->id)
                ->first();

        return PaginatedProductData::from(
            $product
        );

    }

    /**
     * Update the specified resource in storage.
     */
    #[OAT\Patch(path: '/admin/products/{id}', tags: ['products'])]
    #[FormDataRequestBody(UpdateProductData::class)]
    #[SuccessNoContentResponse('Product successfully updated')]
    public function update(
        AdminProductIdPathParameterData $request,
        UpdateProductData $updatedProductData,
    ) {
        $product = Product::find($request->id)
            ->with('categories.parent');

        $is_product_updated = $product->update($request->all());

        // if ($is_product_updated) {
        //     FileService::delete('product', $product->first()->image);
        // }

    }

    #[OAT\Get(path: '/admin/products/getProductsByName', tags: ['products'])]
    #[QueryParameter('name')]
    #[SuccessListResponse(ProductListData::class)]
    public function getProductsByName(ProductNameQueryParameterData $query_parameters)
    {
        $productList = Product::select(['id', 'name']);

        $products = $query_parameters->name ?
            $productList->hasName($query_parameters->name)->get()
            :
            $productList->get();

        return ProductListData::collect($products);

    }

    #[OAT\Post(path: '/admin/products', tags: ['products'])]
    #[FormDataRequestBody(CreateProductData::class)]
    #[SuccessNoContentResponse('Product successfully created')]
    public function store(CreateProductData $request)
    {
        Log::info('accessing ProductController store method');

        $productImage = $request->image;

        $uploadedFileUrl = FileService::upload('product', $productImage);

        $createdProduct = Product::create($request->all());

    }

    #[OAT\Patch(path: '/admin/products/{id}/activate', tags: ['products'])]
    #[SuccessNoContentResponse('Fetched Product Successfully')]
    public function activate(AdminProductIdPathParameterData $request)
    {
        $product = Product::find($request->id);

        $product->is_active = true;

        $product->save();

    }

    #[OAT\Patch(path: '/admin/products/{id}/deActivate', tags: ['products'])]
    #[SuccessNoContentResponse('Product DeActivated Successfully')]
    public function deActivate(AdminProductIdPathParameterData $request)
    {
        $product = Product::find($request->id);

        $product->is_active = false;

        $product->save();

    }
}
