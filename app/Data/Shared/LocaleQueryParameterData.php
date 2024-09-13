<?php

namespace App\Data\Shared;

use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Attributes\Validation\In;
use Spatie\LaravelData\Data;

class LocaleQueryParameterData extends Data
{
    public function __construct(
        #[OAT\QueryParameter(
            parameter: 'localeQueryParameter', //the name used in ref
            name: 'locale', // the name used in swagger, becomes the ref in case the parameter is missing
            required: false,
            schema: new OAT\Schema(
                type: 'string'
            )
        ),
            In('en', 'ar')]
        public ?string $locale,
    ) {
    }
}
