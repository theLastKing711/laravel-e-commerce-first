<?php

namespace App\Data\Shared\Swagger\Property;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_PROPERTY | \Attribute::TARGET_PARAMETER | \Attribute::TARGET_CLASS_CONSTANT | \Attribute::IS_REPEATABLE)]
class DateProperty extends OAT\Property
{
    public function __construct(string $default = '2024-09-02 18:31:45')
    {
        parent::__construct(
            schema: 'string',
            type: 'string',
            format: 'datetime',
            default: $default,
            pattern: 'YYYY-MM-DD',
            example: '2024-09-02 18:31:45',
        );
    }
}
