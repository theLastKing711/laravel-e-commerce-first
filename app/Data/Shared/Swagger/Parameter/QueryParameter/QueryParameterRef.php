<?php

namespace App\Data\Shared\Swagger\Parameter\QueryParameter;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::IS_REPEATABLE)]
class QueryParameterRef extends OAT\QueryParameter
{
    public function __construct(string $name)
    {
        parent::__construct(
            name: $name,
            schema: new OAT\Schema(),
        );
    }
}
