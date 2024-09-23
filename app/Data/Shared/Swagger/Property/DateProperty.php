<?php

namespace App\Data\Shared\Swagger\Property;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_CLASS_CONSTANT | \Attribute::IS_REPEATABLE)]
class DateProperty extends OAT\Property
{
    public function __construct(string $default = '2017-02-02 18:31:45')
    {
        parent::__construct(
            type: 'string',
            format: 'datetime',
            default: '2017-02-02 18:31:45',
            pattern: 'YYYY-MM-DD'
        );
    }
}
