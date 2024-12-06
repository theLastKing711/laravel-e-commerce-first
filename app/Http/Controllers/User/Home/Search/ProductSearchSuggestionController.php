<?php

namespace App\Http\Controllers\User\Home\Search;

use App\Data\Shared\Swagger\Parameter\QueryParameter\QueryParameter;
use App\Data\Shared\Swagger\Response\SuccessListResponse;
use App\Data\User\Home\CursorPaginatedSearchSuggestionData;
use App\Data\User\Home\ProductSearchSuggestionData;
use App\Data\User\Shared\QueryParameters\SearchSuggestionQueryParameterData;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Log;
use OpenApi\Attributes as OAT;

class ProductSearchSuggestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    #[OAT\Get(path: '/user/home/search-suggestion-list', tags: ['userSearch'])]
    #[QueryParameter('search')]
    #[SuccessListResponse(CursorPaginatedSearchSuggestionData::class)]
    public function __invoke(Request $request, SearchSuggestionQueryParameterData $queryParameters)
    {

        $request_search = $queryParameters->search;

        Log::info(
            'Accessing User SearchSuggestionController with search {search}',
            ['search' => $request_search]
        );

        if ($request_search === '') {
            return [];
        }

        $search_result = Product::query()
            // ->select('id', 'name')
            ->whereLike('name', $request_search)
            ->with(
                [
                    'medially',
                    'variants' => [
                        'variantValues' => function ($query) {
                            $query
                                ->with(
                                    [
                                        'medially',
                                        'combinations' => function ($query) {
                                            $query
                                                ->with(
                                                    [
                                                        'medially',
                                                        'pivot' => [
                                                            'medially',
                                                            'first_variant_value',
                                                            'combinations' => [
                                                                'pivot' => [
                                                                    'medially',
                                                                    'variantCombination' => [
                                                                        'first_variant_value',
                                                                    ],
                                                                ],
                                                            ],
                                                        ],
                                                    ]
                                                );
                                        },
                                    ]
                                );
                        },
                    ],
                ]
            )
            ->cursorPaginate(15);

        Log::info('next cursor value {cursor}', ['cursor' => $request->query('cursor')]);

        return ProductSearchSuggestionData::collect($search_result);

    }
}
