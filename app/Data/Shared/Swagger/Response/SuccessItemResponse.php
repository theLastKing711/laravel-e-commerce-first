<?php

namespace App\Data\Shared\Swagger\Response;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SuccessItemResponse extends OAT\Response
{
    public function __construct(
        string $type,
        string $description = 'Item fetched successfully'
    ) {

        parent::__construct(
            response: 200,
            description: $description,
            content: new OAT\JsonContent(type: $type)
        );
    }
}
