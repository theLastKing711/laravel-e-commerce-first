<?php

namespace App\Http\Controllers\Admin;

use App\Data\Admin\Product\CreateProductData;
use App\Data\Admin\Product\PathParameters\ProductIdPathParameterData;
use App\Data\Admin\Product\ProductData;
use App\Data\Admin\Product\ProductListData;
use App\Data\Admin\Product\QueryParameters\ProductNameQueryParameterData;
use App\Data\Admin\Product\UpdateProductData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\FileService;
use Illuminate\Http\Request;
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
    #[OAT\Get(
        path: '/admin/products',
        tags: ['products'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'The Product was successfully created',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: ProductData::class
                    ),
                ),
            ),
        ],
    )]
    public function index(Request $request)
    {

        Log::info('accessing ProductController index method');

        return ProductData::collect(
            Product::with('categories.parent')
                ->get()
        );
        //            ->map(
        //                function (Product $product) {
        //                    $productCategories = $product->categories;
        //
        //                    return ProductData::from([
        //                        'id' => $product->id,
        //                        'name' => $product->name,
        //                        'price' => $product->price,
        //                        'description' => $product->description,
        //                        'unit' => $product->unit,
        //                        'unit_value' => $product->unit_value,
        //                        'price_offer' => $product->price_offer,
        //                        'is_active' => $product->is_active,
        //                        'is_most_buy' => $product->is_most_buy,
        //                        'image' => $product->image,
        //                        'childCategories' => CategoryData::collect($productCategories),
        //                        'parentCategories' => CategoryData::collect(
        //                            $productCategories->pluck('parent')->unique()
        //                        ),
        //                        'created_at' => $product->created_at,
        //                    ]);
        //                }
        //            );

    }

    #[OAT\Get(
        path: '/admin/products/{id}',
        tags: ['products'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'product fetched successfully',
                content: new OAT\JsonContent(type: ProductData::class),
            ),
        ],
    )]
    public function show(ProductIdPathParameterData $request)
    {

        $product = Product::with(['categories.parent'])
            ->where('id', $request->id)
            ->first();

        return ProductData::from(
            $product
        );

    }

    /**
     * Update the specified resource in storage.
     */
    #[OAT\Patch(
        path: '/admin/products/{id}',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OAT\Schema(
                    type: UpdateProductData::class,
                ),
            ),
        ),
        tags: ['products'],
        responses: [
            new OAT\Response(
                response: 200,
                description: 'Product was updated successfully',
                content: new OAT\JsonContent(
                    type: 'array',
                    items: new OAT\Items(
                        type: ProductData::class,
                    ),
                ),
            ),
        ],
    )]
    public function update(
        ProductIdPathParameterData $request,
        UpdateProductData $updatedProductData,
    ) {
        $product = Product::find($request->id)
            ->with('categories.parent');

        $isProductUpdated = $product->update($request->all());

        if ($isProductUpdated) {
            FileService::delete('product', $product->first()->image);
        }

        return ProductData::from($product);

    }

    #[OAT\Post(
        path: '/admin/products/getProductsByName',
        tags: ['products'],
        parameters: [
            new OAT\QueryParameter(
                required: false,
                ref: '#/components/parameters/adminProductName',
            ),
        ],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Product Successfully',
                content: new OAT\JsonContent(type: ProductListData::class),
            ),
        ],
    )]
    public function getProductsByName(ProductNameQueryParameterData $request)
    {
        $productList = Product::select(['id', 'name']);

        $products = $request->name ?
            $productList->hasName($request->name)->get()
            :
            $productList->get();

        return ProductListData::collect($products);

    }

    #[OAT\Post(
        path: '/admin/products',
        requestBody: new OAT\RequestBody(
            required: true,
            content: new OAT\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OAT\Schema(
                    type: CreateProductData::class,
                ),
            ),
        ),
        tags: ['products'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Product Successfully',
                content: new OAT\JsonContent(type: ProductListData::class),
            ),
        ],
    )]
    public function store(CreateProductData $request)
    {
        Log::info('accessing ProductController store method');

        $productImage = $request->image;

        $uploadedFileUrl = FileService::upload('product', $productImage);

        $createdProduct = Product::create($request->all());

        Log::info('type of unit is {unit} ', ['unit' => $createdProduct]);

        return ProductData::from($createdProduct);
    }

    #[OAT\Patch(
        path: '/admin/products/{id}/activate',
        tags: ['products'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Product Successfully',
                content: new OAT\JsonContent(type: ProductListData::class),
            ),
        ],
    )]
    public function activate(ProductIdPathParameterData $request)
    {
        $product = Product::find($request->id);

        $product->is_active = true;

        $product->save();

        return ProductData::from($product);

    }

    #[OAT\Patch(
        path: '/admin/products/{id}/deActivate',
        tags: ['products'],
        responses: [
            new OAT\Response(
                response: 204,
                description: 'Fetched Product Successfully',
                content: new OAT\JsonContent(type: ProductListData::class),
            ),
        ],
    )]
    public function deActivate(ProductIdPathParameterData $request)
    {
        $product = Product::find($request->id);

        $product->is_active = false;

        $product->save();

        return ProductData::from($product);

    }
}
