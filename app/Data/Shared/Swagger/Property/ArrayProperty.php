<?php

namespace App\Data\Shared\Swagger\Property;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_CLASS_CONSTANT | \Attribute::IS_REPEATABLE)]
class ArrayProperty extends OAT\Property
{

    public function __construct(string $type = 'integer')
    {
        parent::__construct(
            type: 'array',
            items: new OAT\Items(
                type: $type
            )
        );
    }
}
