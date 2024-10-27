<?php

namespace App\Data\User\Shared\QueryParameters;

use Spatie\LaravelData\Data;

class SearchSuggestionQueryParameterData extends Data
{
    public function __construct(
        public ?string $search,
        public ?string $next_cursor
    ) {
    }
}
