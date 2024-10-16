<?php

namespace App\Data\Shared\Swagger\Response;

use OpenApi\Attributes as OAT;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD | \Attribute::IS_REPEATABLE)]
class FailureAuthenticationFailedResponse extends OAT\Response
{
    public function __construct(
        string $description = 'Authentication Failed'
    ) {

        parent::__construct(
            response: 401,
            description: $description,
            content: new OAT\JsonContent(type: 'string')
        );
    }
}
