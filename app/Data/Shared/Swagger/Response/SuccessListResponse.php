<?php

namespace App\Data\Shared\Swagger\Response;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class SuccessListResponse extends OAT\Response
{
    public function __construct(
        string $type,
        string $description = 'Items fetched successfully'
    ) {
        parent::__construct(
            response: 200,
            description: $description,
            content: new OAT\JsonContent(
                type: 'array',
                items: new OAT\Items(
                    type: $type
                )
            )
        );
    }
}
