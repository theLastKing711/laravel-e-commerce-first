<?php

namespace App\Data\Shared\Swagger\Parameter\QueryParameter;

use OpenApi\Attributes as OAT;
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::IS_REPEATABLE)]
class ListQueryParameterRef extends OAT\QueryParameter
{
    public function __construct(string $name = 'ids', string $type = 'integer')
    {
        parent::__construct(
            name: 'ids', // the name used in swagger ui, becomes the ref in case the parameter is missing
            schema: new OAT\Schema(type: $type),

        );
    }
}

