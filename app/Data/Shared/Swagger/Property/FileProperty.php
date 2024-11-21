<?php

namespace App\Data\Shared\Swagger\Property;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_CLASS_CONSTANT | \Attribute::IS_REPEATABLE)]
class FileProperty extends OAT\Property
{
    public function __construct()
    {
        parent::__construct(
            type: 'string',
            format: 'binary',
        );
    }
}
