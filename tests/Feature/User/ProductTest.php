<?php

namespace Tests\Feature\User;

use App\Data\Admin\User\Product\Details\GetProductDetailsData;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\SecondVariantCombination;
use App\Models\Variant;
use App\Models\VariantCombination;
use App\Models\VariantValue;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class ProductTest extends UserTestCase
{
    private string $main_route = '/user/products';

    protected function setUp(): void
    {
        parent::setUp();
    }

    #[Test]
    public function index_product_with_no_variant_returns_no_variation_or_variants(): void
    {

        /** @var Product $request_product_with_one_variant */
        $request_product_with_one_variant =
            Product::query()
                ->has('variants', 0)
                ->first();

        $show_route =
            $this
                ->main_route.
                '/'.
                $request_product_with_one_variant->id;

        $response = $this->get($show_route);

        $response->assertStatus(200);

        $products_response_data =
            $response
                ->json();

        $product_response_data =
            GetProductDetailsData::from($products_response_data);

        $response_product_id_is_same_as_request_route_parameter =
            $request_product_with_one_variant
                ->id
            ==
            $product_response_data
                ->id;

        $this->assertTrue($response_product_id_is_same_as_request_route_parameter);

        $response_product_data_has_no_variants =
            $product_response_data
                ->variants
                ->isEmpty();

        $this->assertTrue($response_product_data_has_no_variants);

        $response_product_data_has_no_variation =
            $product_response_data
                ->variation
                ==
                null;

        $this->assertTrue($response_product_data_has_no_variation);

    }

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

    }

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

    #[Test]
    public function index_product_with_two_variants_and_second_variant_and_variant_value_that_has_no_combination_at_all_should_return_product_with_no_variation_and_no_combinations_for_that_second_variant_value_but_other_variant_values_with_combinatios_should_link_to_them_in_combinations(): void
    {
        $product =
            Product::factory()->withImage()->createOne();

        $category =
            Category::factory()->createOne();

        $category->products()->save($product);

        $variants =
            Variant::factory(2)->make();

        $product->variants()->saveMany($variants);

        $first_variant_variant_values =
            VariantValue::factory(2)
                ->withImage()
                ->make();

        $first_variant = $variants->first();

        $first_variant_variant_values =
        VariantValue::factory(2)
            ->withImage()
            ->create(['variant_id' => $first_variant->id]);

        /** @var VariantValue $first_variant_first_variant_value */
        $first_variant_first_variant_value = $first_variant_variant_values->first();

        $second_variant = $variants->skip(1)->first();
        $second_variant_variant_values =
            VariantValue::factory(2)
                ->withImage()
                ->create(['variant_id' => $second_variant->id]);

        /** @var VariantValue $second_variant_first_variant_value */
        $second_variant_first_variant_value = $second_variant_variant_values->first();
        $second_variant_first_variant_value
            ->combinations()
            ->save($first_variant_first_variant_value, ['is_thumb' => true, 'price' => 25, 'available' => 0]);

        /** @var VariantValue $second_variant_second_variant_value */
        $second_variant_second_variant_value = $second_variant_variant_values->skip(1)->first();

        $variant_combinations_media = Media::factory(2)->make();

        /** @var VariantCombination $first_variant_combination */
        $first_variant_combination = VariantCombination::query()
            ->where('first_variant_value_id', $second_variant_first_variant_value->id)
            ->where('second_variant_value_id', $first_variant_first_variant_value->id)
            ->first();

        /** @var Media $first_combianation_media */
        $first_combianation_media = $variant_combinations_media->first();

        $first_variant_combination
            ->medially()
            ->save($first_combianation_media);

        /** @var Media $second_combianation_media */
        $second_combianation_media = $variant_combinations_media->skip(1)->first();

        $first_variant_combination
            ->medially()
            ->save($second_combianation_media);

        /** @var Product $request_product_with_two_variant */
        $request_product_with_two_variant =
            Product::query()
                ->whereRelation('variants.variantValues', 'variant_values.id', '=', $second_variant_second_variant_value->id)
                ->with([
                    'medially',
                    'variants' => [
                        'variantValues' => [
                            'medially',
                            'combinations' => [
                                'pivot',
                            ],
                        ],
                    ],
                ])
                ->first();

        $show_route =
            $this
                ->main_route.
                '/'.
                $request_product_with_two_variant->id.
                '?second_variant_value_id='.
                $second_variant_second_variant_value->id;

        $response = $this->get($show_route);

        $response->assertStatus(200);

        $products_response_data =
            $response
                ->json();

        $product_response_data =
            GetProductDetailsData::from($products_response_data);

        $response_product_id_is_same_as_request_route_parameter =
            $request_product_with_two_variant
                ->id
            ==
            $product_response_data
                ->id;

        $this->assertTrue($response_product_id_is_same_as_request_route_parameter);

        $response_product_data_has_no_variation =
            $product_response_data
                ->variation
                ==
                null;

        $this->assertTrue(
            $response_product_data_has_no_variation
        );

        $product_response_data_first_variant_and_variant_value =
            $product_response_data
                ->variants
                ->first()
                ->variant_values
                ->first();

        $product_response_data_second_variant_first_variant_value =
            $product_response_data
                ->variants
                ->skip(1)
                ->first()
                ->variant_values
                ->first();

        $response_product_data_first_variant_and_variant_value_has_combination_with_second_variant_first_variant_value =
            $product_response_data_first_variant_and_variant_value
                ->id
            ==
            $product_response_data_first_variant_and_variant_value
                ->combinations_ids_with_selected_variant_value
                ->first_variant_value_id
            &&
            $product_response_data_second_variant_first_variant_value
                ->id
            ==
            $product_response_data_first_variant_and_variant_value
                ->combinations_ids_with_selected_variant_value
                ->second_variant_value_id;

        $this->assertTrue($response_product_data_first_variant_and_variant_value_has_combination_with_second_variant_first_variant_value);

        $product_response_data_second_variant_and_variant_value =
        $product_response_data
            ->variants
            ->skip(1)
            ->first()
            ->variant_values
            ->skip(1)
            ->first();

        $response_product_data_second_variant__second_variant_value_has_no_combinations =
            $product_response_data_second_variant_and_variant_value
                ->id
            ==
            $product_response_data_second_variant_and_variant_value
                ->combinations_ids_with_selected_variant_value
                ->second_variant_value_id
            &&
            $product_response_data_second_variant_and_variant_value
                ->combinations_ids_with_selected_variant_value
                ->first_variant_value_id
            ==
            null;

        $this->assertTrue($response_product_data_second_variant__second_variant_value_has_no_combinations);

    }
}
