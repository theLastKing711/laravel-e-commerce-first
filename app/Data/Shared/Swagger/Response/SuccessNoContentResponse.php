<?php

namespace App\Data\Shared\Swagger\Response;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SuccessNoContentResponse extends OAT\Response
{
    public function __construct(string $description = '')
    {
        parent::__construct(
            response: 204,
            description: $description,
        );
    }
}

