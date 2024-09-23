<?php

namespace App\casts;

use Illuminate\Support\Facades\Log;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\Creation\CreationContext;
use Spatie\LaravelData\Support\DataProperty;

class BoolCast implements Cast
{
    public function cast(DataProperty $property, mixed $value, array $properties, CreationContext $context): mixed
    {
        Log::info('valuess');

        return (bool) $value;
    }
}
