<?php

namespace App\Data\Shared\Swagger\Request;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class JsonRequestBody extends OAT\RequestBody
{
    public function __construct(string $type, bool $required = true)
    {
        parent::__construct(
            required: $required,
            content: new OAT\JsonContent(type: $type),
        );
    }
}
