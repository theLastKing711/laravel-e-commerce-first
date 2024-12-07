<?php

namespace Tests\Feature\User;

use App\Data\User\Home\ProductSearchSuggestionData;
use App\Models\Category;
use App\Models\Media;
use App\Models\Product;
use App\Models\SecondVariantCombination;
use App\Models\Variant;
use App\Models\VariantCombination;
use App\Models\VariantValue;
use Illuminate\Support\Collection;
use PHPUnit\Framework\Attributes\Test;
use Tests\Feature\User\Abstractions\UserTestCase;

class ProductSearchSuggestionTest extends UserTestCase
{
    private string $main_route = '/user/home/search-suggestion-list';

    #[Test]
    public function index_searching_product_that_has_three_variants_returns_correct_data(): void
    {
        $request_search_target_product_with_three_variant =
            Product::factory()
                ->withImage()
                ->state(['name' => 'buffallo'])
                ->createOne();

        $product_with_three_variants =
            Product::factory()
                ->withImage()
                ->state(['name' => 'rs'])
                ->createOne();

        $category =
            Category::factory()->createOne();

        $category->products()->save($request_search_target_product_with_three_variant);

        $variants =
            Variant::factory(3)->make();

        $request_search_target_product_with_three_variant->variants()->saveMany($variants);

        $first_variant_first_variant_value =
            VariantValue::factory(1)
                ->withImage()
                ->createOne(['variant_id' => $variants->first()->id]);

        $second_variant_first_variant_value =
            VariantValue::factory(1)
                ->withImage()
                ->createOne(['variant_id' => $variants->skip(1)->first()->id]);

        $second_variant_first_variant_value
            ->combinations()
            ->save($first_variant_first_variant_value, ['is_thumb' => true, 'price' => 25, 'available' => 0]);

        $variant_combinations_media = Media::factory(1)->make();

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

        $thumb_third_variant_first_variant_value =
            VariantValue::factory(1)
                ->withImage()
                ->createOne(['variant_id' => $variants->skip(2)->first()]);

        $thumb_third_variant_first_variant_value
            ->late_combinations()
            ->save($first_variant_combination, ['is_thumb' => true, 'price' => 25, 'available' => 0]);

        /** @var Media $thumb_third_variant_first_variant_value_second_variant_combination_media */
        $thumb_third_variant_first_variant_value_second_variant_combination_media = Media::factory(1)->makeOne();

        /** @var SecondVariantCombination $thumb_third_variant_first_variant_value_second_variant_combination */
        $thumb_third_variant_first_variant_value_second_variant_combination =
            SecondVariantCombination::query()
                ->firstWhere('variant_value_id', $thumb_third_variant_first_variant_value->id);

        $thumb_third_variant_first_variant_value_second_variant_combination
            ->medially()
            ->save($thumb_third_variant_first_variant_value_second_variant_combination_media);

        $third_variant_second_variant_value =
            VariantValue::factory(1)
                ->withImage()
                ->createOne(['variant_id' => $variants->skip(2)->first()]);

        /** @var Media $third_variant_second_variant_valuesecond_variant_combianation_media */
        $third_variant_second_variant_valuesecond_variant_combianation_media = Media::factory(1)->makeOne();

        $thumb_third_variant_first_variant_value_second_variant_combination
            ->medially()
            ->save($third_variant_second_variant_valuesecond_variant_combianation_media);

        $third_variant_second_variant_value
            ->late_combinations()
            ->save($first_variant_combination, ['is_thumb' => false, 'price' => 25, 'available' => 0]);

        /** @var SecondVariantCombination $third_variant_second_variant_value_second_variant_combination */
        $third_variant_second_variant_value_second_variant_combination =
            SecondVariantCombination::query()
                ->firstWhere('variant_value_id', $third_variant_second_variant_value->id);

        $show_route = $this->main_route.'?search=b';

        $response = $this->get($show_route);

        $response->assertStatus(200);

        $products_search_response_json =
            $response
                ->json();

        $products_search_response_data =
            ProductSearchSuggestionData::collect($products_search_response_json['data'], Collection::class);

        $response_search_target_product_data =
            $products_search_response_data
                ->firstWhere('name', 'buffallo');

        $response_product_data_has_search_target_product_only =
            $response_search_target_product_data
                ->id
            ==
            $request_search_target_product_with_three_variant
                ->id;

        $this->assertTrue($response_product_data_has_search_target_product_only);

        $response_search_target_product_data_variation_is_thumb =
            $response_search_target_product_data
                ->product_variation
                ->id
                ==
                $thumb_third_variant_first_variant_value_second_variant_combination
                    ->id;

        $this->assertTrue($response_search_target_product_data_variation_is_thumb);

        $response_search_target_product_data_first_query_parameter_is_valid =
            $response_search_target_product_data
                ->variant_value_ids_query_parameters
                ->first_variant_value_id
            ==
            $first_variant_first_variant_value
                ->id;

        $this->assertTrue($response_search_target_product_data_first_query_parameter_is_valid);

        $response_search_target_product_data_second_query_parameter_is_valid =
            $response_search_target_product_data
                ->variant_value_ids_query_parameters
                ->second_variant_value_id
            ==
            $second_variant_first_variant_value
                ->id;

        $this->assertTrue($response_search_target_product_data_second_query_parameter_is_valid);

        $response_search_target_product_data_third_query_parameter_is_valid =
            $response_search_target_product_data
                ->variant_value_ids_query_parameters
                ->third_variant_value_id
            ==
            $thumb_third_variant_first_variant_value
                ->id;

        $this->assertTrue($response_search_target_product_data_third_query_parameter_is_valid);

    }
}
