<?php

namespace App\Data\Shared\Swagger\Property;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::IS_REPEATABLE)]
class QueryParameter extends OAT\QueryParameter
{
    public function __construct(string $name, string $type = 'string')
    {
        parent::__construct(
            //            parameter: $name, //the name used in ref
            name: $name, // the name used in swagger ui, becomes the ref in case the parameter is missing
            schema: new OAT\Schema(type: $type),
        );
    }
}
