<?php

namespace Tests\Feature\User;

use App\Data\Admin\User\Product\Details\GetProductDetailsData;
use App\Models\Category;
use App\Models\Product;
use App\Models\SecondVariantCombination;
use App\Models\Variant;
use App\Models\VariantCombination;
use Illuminate\Support\Facades\Log;
use Illuminate\Testing\Fluent\AssertableJson;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class ProductTest extends UserTestCase
{
    private string $main_route = '/user/products';

    public function setUp(): void
    {

        parent::setUp();
    }

    /**
     * A basic feature test example.
     */
    #[Test]
    public function index_product_with_one_variant_returns_correct_response_data(): void
    {

        /** @var Product $request_product_with_one_variant */
        $request_product_with_one_variant =
            Product::query()
                ->has('variants', 1)
                ->with([
                    'variants' => [
                        'variantValues',
                    ],
                ])
                ->first();

        $product_first_variant_value =
            $request_product_with_one_variant
                ->variants
                ->first()
                ->variantValues
                ->first();

        $show_route =
            $this
                ->main_route.
                '/'.
                $request_product_with_one_variant->id.
                '?first_variant_value_id='.
                $product_first_variant_value->id;

        $response = $this->get($show_route);

        $response->assertStatus(200);

        $products_response_data =
            $response
                ->json();

        $product_response_data =
            GetProductDetailsData::from($products_response_data);

        $response_has_correct_product_id =
            $product_response_data->id
            ==
            $request_product_with_one_variant->id;

        $response_has_correct_product_variation_id =
            $product_response_data->variation->id
            ==
            $product_first_variant_value->id;

        $response_product_has_one_variant_only =
            $product_response_data->variants->count()
            ==
            1;

        $response_selected_first_variant_variant_values =
            $product_response_data
                ->variants
                ->first()
                ->variant_values
                ->filter(function ($variant_value) {

                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_first_variant =
            $response_selected_first_variant_variant_values
            ==
            1;

        $this->assertTrue($response_has_correct_product_id);

        $this->assertTrue($response_has_correct_product_variation_id);

        $this->assertTrue($response_product_has_one_variant_only);

        $this->assertTrue($repsone_product_has_only_one_variant_value_selected_from_first_variant);

        //     $response
        //         ->assertJson(
        //             fn (AssertableJson $json) =>
        // //                        ->tap(fn (AssertableJson $json) => Log::info($json))
        //                 $json
        //                     ->where('id', $first_db_parent_category->id)
        //                     ->where('name', $first_db_parent_category->name)
        //                     ->where('parent_id', $first_db_parent_category->parent_id)
        //                     ->where('parent_name', $first_db_parent_category->parent?->name)
        //                     ->etc() // means don't need to specify all properties in json('data') here
        //         );

        // Log::info($products_response_data);

        //         $product_with_three_variants_id =
        //             Product::query()
        //                 ->has('variants', 3);

        //         $product_details_route =
        //             $this->main_route. '/'

        //         $response = $this->get($this->main_route);

        //         $response->assertStatus(200);

        //         $categories_response_data = $response->json()['data'];

        //         $this->assertIsArray($categories_response_data);

        //         $first_db_parent_category = Category::query()
        //             ->with('parent')
        //             ->first();

        //         $response
        //             ->assertJson(
        //                 fn (AssertableJson $json) => $json->has(
        //                     'data',
        //                     10,
        //                     fn (AssertableJson $json) => $json // runs one first item of json('data')
        // //                        ->tap(fn (AssertableJson $json) => Log::info($json))
        //                         ->where('id', $first_db_parent_category->id)
        //                         ->where('name', $first_db_parent_category->name)
        //                         ->where('parent_id', $first_db_parent_category->parent_id)
        //                         ->where('parent_name', $first_db_parent_category->parent?->name)
        //                         ->etc() // means don't need to specify all properties in json('data') here
        //                 )
        //                     ->etc()// means don't need to specify all properties in json here
        //             );

    }

    /**
     * A basic feature test example.
     */
    #[Test]
    public function index_product_with_two_variant_returns_correct_response_data(): void
    {

        /** @var Product $request_product_with_two_variant */
        $request_product_with_two_variant =
            Product::query()
                ->has('variants', 2)
                ->with([
                    'variants' => [
                        'variantValues' => [
                            'combinations' => [
                                'pivot' => [
                                    'first_variant_value',
                                    'second_variant_value',
                                ],
                            ],
                        ],
                    ],
                ])
                ->first();

        /** @var VariantCombination $request_product_variant_combination description */
        $request_product_variant_combination =
            $request_product_with_two_variant
                ->variants
                ->selectMany('variantValues')
                ->selectMany('combinations')
                ->pluck('pivot')
                ->first();

        $request_product_first_variant_value =
                    $request_product_variant_combination
                        ->second_variant_value;

        $product_second_variant_value =
            $request_product_variant_combination
                        ->first_variant_value;

        $show_route =
            $this
                ->main_route.
                '/'.
                $request_product_with_two_variant->id.
                '?first_variant_value_id='.
                $request_product_first_variant_value->id.
                '&second_variant_value_id='.
                $product_second_variant_value->id;

        $response = $this->get($show_route);

        $response->assertStatus(200);

        $products_response_data =
            $response
                ->json();

        $product_response_data =
            GetProductDetailsData::from($products_response_data);

        $response_has_correct_product_id =
            $product_response_data->id
            ==
            $request_product_with_two_variant->id;

        $response_has_correct_product_variation_id =
            $product_response_data->variation->id
            ==
            $request_product_variant_combination->id;

        $response_product_has_two_variant_only =
            $product_response_data->variants->count()
            ==
            2;

        /** @var Variant $response_product_first_variant */
        $response_product_first_variant =
            $product_response_data
                ->variants
                ->first();

        $response_selected_first_variant_variant_values_count =
            $response_product_first_variant
                ->variant_values
                ->filter(function ($variant_value) {

                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_first_variant =
            $response_selected_first_variant_variant_values_count
            ==
            1;

        /** @var Variant $response_product_second_variant */
        $response_product_second_variant =
            $product_response_data
                ->variants
                ->skip(1)
                ->first();

        $response_selected_second_variant_variant_values_count =
            $response_product_second_variant
                ->variant_values
                ->filter(function ($variant_value) {
                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_first_variant =
            $response_selected_second_variant_variant_values_count
            ==
            1;

        $response_selected_second_variant_variant_values_count =
            $product_response_data
                ->variants
                ->first()
                ->variant_values
                ->filter(function ($variant_value) {
                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_second_variant =
            $response_selected_second_variant_variant_values_count
            ==
            1;

        $this->assertTrue($response_has_correct_product_id);

        $this->assertTrue($response_has_correct_product_variation_id);

        $this->assertTrue($response_product_has_two_variant_only);

        $this->assertTrue($repsone_product_has_only_one_variant_value_selected_from_first_variant);

        $this->assertTrue($repsone_product_has_only_one_variant_value_selected_from_second_variant);

    }

    #[Test]
    public function index_product_with_three_variant_returns_correct_response_data(): void
    {

        /** @var Product $request_product_with_three_variants */
        $request_product_with_three_variants =
            Product::query()
                ->has('variants', 3)
                ->with([
                    'variants' => [
                        'variantValues' => [
                            'combinations' => [
                                'pivot' => [
                                    'combinations' => [
                                        'pivot' => [
                                            'variantValue',
                                            'variantCombination' => [
                                                'first_variant_value',
                                                'second_variant_value',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ])
                ->first();

        /** @var SecondVariantCombination $request_product_second_variant_combination */
        $request_product_second_variant_combination =
            $request_product_with_three_variants
                ->variants
                ->selectMany('variantValues')
                ->selectMany('combinations')
                ->pluck('pivot')
                ->selectMany('combinations')
                ->pluck('pivot')
                ->first();

        $request_product_variant_combination =
            $request_product_second_variant_combination
                ->variantCombination;

        $request_product_first_variant_value =
            $request_product_variant_combination
                    ->second_variant_value;

        $request_product_second_variant_value =
            $request_product_variant_combination
                        ->first_variant_value;

        $request_product_third_variant_value =
            $request_product_second_variant_combination
                        ->variantValue;

        $show_route =
            $this
                ->main_route.
                '/'.
                $request_product_with_three_variants->id.
                '?first_variant_value_id='.
                $request_product_first_variant_value->id.
                '&second_variant_value_id='.
                $request_product_second_variant_value->id.
                '&third_variant_value_id='.
                $request_product_third_variant_value->id;

        $response = $this->get($show_route);

        $response->assertStatus(200);

        $products_response_data =
            $response
                ->json();

        $product_response_data =
            GetProductDetailsData::from($products_response_data);

        $response_has_correct_product_id =
            $product_response_data->id
            ==
            $request_product_with_three_variants->id;

        $response_has_correct_product_variation_id =
            $product_response_data->variation->id
            ==
            $request_product_second_variant_combination->id;

        $response_product_has_three_variant_only =
            $product_response_data->variants->count()
            ==
            3;

        /** @var Variant $response_product_first_variant */
        $response_product_first_variant =
            $product_response_data
                ->variants
                ->first();

        $response_selected_first_variant_variant_values_count =
            $response_product_first_variant
                ->variant_values
                ->filter(function ($variant_value) {

                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_first_variant =
            $response_selected_first_variant_variant_values_count
            ==
            3;

        /** @var Variant $response_product_second_variant */
        $response_product_second_variant =
            $product_response_data
                ->variants
                ->skip(1)
                ->first();

        $response_selected_second_variant_variant_values_count =
            $response_product_second_variant
                ->variant_values
                ->filter(function ($variant_value) {
                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_second_variant =
            $response_selected_second_variant_variant_values_count
            ==
            1;

        /** @var Variant $response_product_third_variant */
        $response_product_three_variant =
            $product_response_data
                ->variants
                ->skip(2)
                ->first();

        $response_selected_third_variant_variant_values_count =
            $response_product_three_variant
                ->variant_values
                ->filter(function ($variant_value) {
                    return $variant_value->is_selected == true;
                })
                ->count();

        $repsone_product_has_only_one_variant_value_selected_from_third_variant =
            $response_selected_second_variant_variant_values_count
            ==
            1;

        $this->assertTrue($response_has_correct_product_id);

        $this->assertTrue($response_has_correct_product_variation_id);

        $this->assertTrue($response_product_has_three_variant_only);

        $this->assertTrue($repsone_product_has_only_one_variant_value_selected_from_first_variant);

        $this->assertTrue($repsone_product_has_only_one_variant_value_selected_from_second_variant);

        $this->assertTrue($repsone_product_has_only_one_variant_value_selected_from_third_variant);

    }
}
